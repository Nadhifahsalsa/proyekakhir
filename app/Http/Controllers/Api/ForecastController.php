<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\barangKeluar;
use App\Models\BarangMasuk;
use Phpml\TimeSeries\Arima;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        // Prediksi permintaan menggunakan ARIMA
        $prediksi_permintaan = $this->predict($request);

        // Hitung persediaan untuk barang yang diprediksi
        $persediaan = $this->hitungPersediaan($prediksi_permintaan);

        // Logika Fuzzy Tsukamoto
        $hasil_fuzzy = [];
        // dd($persediaan);
        foreach ($persediaan as $nama_barang => $stok) {
            // dd($stok);
            $permintaan = $stok["forecast"] ?? 0;
            $hasil_fuzzy[] = [...$stok, "fuzzy" => $this->fuzzyTsukamoto($stok["jumlah"], $permintaan)];
            // $hasil_fuzzy[$nama_barang] = $this->fuzzyTsukamoto($stok["jumlah"], $permintaan);
        }
        dd($hasil_fuzzy);
        return view('pages.filter', compact('hasil_fuzzy'));
    }

    public function predict(Request $request)
    {

        //     // Ambil data barang keluar dari database
        //     $data = DB::table('barang_keluars')->select('tgl_keluar', 'jumlah_barang', 'barang')->get();

        //     // Konversi data ke format JSON
        //     // $jsonData = escapeshellarg(json_encode($data));
        //     $jsonData = json_encode($data);
        //     // dd(addslashes($escapedJsonData));

        //    // URL to the Flask server
        //     $flaskUrl = 'http://127.0.0.1:5000/run-script';

        //     try {
        //         // Send a POST request to the Flask server
        //         $response = Http::post($flaskUrl, [
        //             'json' => $jsonData
        //         ]);

        //         // Get the response
        //         $content = $response->json();
        //         // dd($response);

        //         // Check for errors in the response
        //         if ($response->failed()) {
        //             return response()->json(['error' => $content['error'], 'output' => $content['output']], 500);
        //         }

        //         return response()->json(['output' => $content['output']]);
        //     } catch (\Exception $e) {
        //         return response()->json(['error' => $e->getMessage()], 500);
        //     }

        // Fetch all columns from the database
        $data = DB::table('barang_keluars')->get();

        // Create a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'export');
        $tempFile = fopen($tempFilePath, 'w');

        // Get the headers (column names)
        $headers = array_keys((array) $data->first());

        // Add the headers to the CSV
        fputcsv($tempFile, $headers);

        // Add the data to the CSV
        foreach ($data as $row) {
            fputcsv($tempFile, (array) $row);
        }

        // Close the file
        fclose($tempFile);

        // Prepare the CURL request to send the CSV file
        $ch = curl_init();

        $cfile = new \CURLFile($tempFilePath, 'text/csv', 'export.csv');

        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:5000/run-script');  // Replace with your Flask endpoint URL
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $cfile]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request and get the response
        $response = curl_exec($ch);

        // Close CURL resource
        curl_close($ch);

        // Remove the temporary file
        unlink($tempFilePath);

        // Decode the JSON response from the Python script
        $response_data = json_decode($response, true);

        // Return the response as JSON
        return ($response_data);
    }

    // public function showPrediction()
    // {
    //     // Mengambil data prediksi dari endpoint predict
    //       $response = Http::get(url('/predict'));
    //     $predictions = $response->json();

    //     return view('pages.filter', ['predictions' => $predictions]);
    //     //return view('pages.filter', ['barangKeluarTiapBulan' => $barangKeluarTiapBulan]);
    // }

    private function hitungPersediaan($prediksi_permintaan)
    {
        $persediaan = [];
        // dd($prediksi_permintaan);

        foreach ($prediksi_permintaan as $barang => $prediksi) {
            // dd($prediksi["barang"]);
            // dd(barangKeluar::where("barang", 1)->sum("jumlah_barang"));
            // Menghitung total barang masuk dan barang keluar untuk setiap barang yang diprediksi
            $total_keluar = BarangKeluar::where('barang', $prediksi["barang"])->get()->sum(function ($e) {
                return (int)$e->jumlah_barang;
            });

            // Menghitung total barang masuk
            $total_masuk = BarangMasuk::where('barang', $prediksi["barang"])->get()->sum(function ($e) {
                return (int)$e->jumlah_barang;
            });

            $persediaan[] = [
                "barang" => $prediksi["barang"],
                "forecast" => $prediksi["forecast"],
                "jumlah" => $total_masuk - $total_keluar,
            ];
        }

        return $persediaan;
    }

    private function fuzzyTsukamoto($stok, $permintaan)
    {
        // Definisikan derajat keanggotaan fuzzy untuk stok
        $stok_kurang = $stok_sedang = $stok_banyak = 0;
        if ($stok <= 50) {
            $stok_kurang = $this->fuzzify($stok, 0, 20, 50);
        } elseif ($stok <= 70) {
            $stok_sedang = $this->fuzzify($stok, 20, 50, 70);
        } else {
            $stok_banyak = $this->fuzzify($stok, 50, 70, 100);
        }

        // Definisikan derajat keanggotaan fuzzy untuk permintaan
        $permintaan_kurang = $permintaan_sedang = $permintaan_banyak = 0;
        if ($permintaan <= 50) {
            $permintaan_kurang = $this->fuzzify($permintaan, 0, 20, 50);
        } elseif ($permintaan <= 70) {
            $permintaan_sedang = $this->fuzzify($permintaan, 20, 50, 70);
        } else {
            $permintaan_banyak = $this->fuzzify($permintaan, 50, 70, 100);
        }

        // Inferensi fuzzy
        $hasil = max(
            min($stok_kurang, $permintaan_kurang),
            min($stok_kurang, $permintaan_sedang),
            min($stok_kurang, $permintaan_banyak),
            min($stok_sedang, $permintaan_kurang),
            min($stok_sedang, $permintaan_sedang),
            min($stok_sedang, $permintaan_banyak),
            min($stok_banyak, $permintaan_kurang),
            min($stok_banyak, $permintaan_sedang),
            min($stok_banyak, $permintaan_banyak)
        );

        return $hasil;
    }

    private function fuzzify($value, $a, $b, $c)
    {
        // dd($value);
        if ($value <= $a) return 0;
        if ($value >= $c) return 0;
        if ($value == $b) return 1;
        if ($value > $a && $value < $b) return ($value - $a) / ($b - $a);
        if ($value > $b && $value < $c) return ($c - $value) / ($c - $b);
        return 0;
    }
}
