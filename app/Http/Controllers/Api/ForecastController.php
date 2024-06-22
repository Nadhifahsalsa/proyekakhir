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

        // foreach ($persediaan as $nama_barang => $stok) {
        //     // dd($stok);
        //     $permintaan = $stok["forecast"] ?? 0;
        //     $hasil_fuzzy[] = [...$stok, "fuzzy" => $this->fuzzyTsukamoto($stok["jumlah"], $permintaan)];
        //     // $hasil_fuzzy[$nama_barang] = $this->fuzzyTsukamoto($stok["jumlah"], $permintaan);
        // }

        foreach ($persediaan as $barang => $stok) {
            if (!isset($stok['barang'])) {
                continue; // Skip this item if 'barang' key does not exist
            }
            
            $permintaan = round($stok['forecast'] ?? 0); // Membulatkan hasil forecast
            $fuzzyResult = $this->determineDemandAndStock($stok['jumlah'], $permintaan);
            // $jumlahPerluDistok = max(0, $permintaan + $stok['jumlah']); // Menghitung jumlah yang perlu distok
            $this->hitung_u($permintaan, $stok['jumlah']);
            $this->hitung_zt();
            $this->hitung_zs($permintaan, $stok['jumlah']);
            list($t_jml_prod, $s_jml_prod) = $this->bobot();
            
            $hasil_fuzzy[] = array_merge($stok, [
                'forecast' => $permintaan, // Update nilai forecast yang sudah dibulatkan
                'forecast_keterangan' => $fuzzyResult['permintaan']['description'],
                'forecast_degree' => $fuzzyResult['permintaan']['degree'],
                'stok_keterangan' => $fuzzyResult['stok']['description'],
                'stok_degree' => $fuzzyResult['stok']['degree'],
                'derajat_keanggotaan' => $fuzzyResult['derajat_keanggotaan'],
                'keterangan_derajat_keanggotaan' => $fuzzyResult['keterangan_derajat_keanggotaan'],
                // 'jumlah_perlu_distok' => $jumlahPerluDistok // Menambahkan jumlah yang perlu distok
                'produksi_tsukamoto' => floor($t_jml_prod),
                // 'produksi_sugeno' => floor($s_jml_prod),
            ]);
        }


        //dd($hasil_fuzzy);
        //return view('pages.filter', compact('hasil_fuzzy'));
        //return view('pages.filter', ['predictions' => $predictions]);
        return view('pages.filter', ['hasil_fuzzy' => $hasil_fuzzy]);
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

    private function fuzzify($value, $a, $b, $c)
    {
        // dd($value);
        if ($value <= $a) return 0;
        if ($value >= $c) return 0;
        if ($value == $b) return 1;
        if ($value > $a && $value < $b) return ($value - $a) / ($b - $a);
        if ($value > $b && $value < $c) return ($c - $value) / ($c - $b);
        return 0;
        //dikasih keterangan derajat keanggotannya
    }

    // private function fuzzyTsukamoto($stok, $permintaan)
    // {
    //     // Definisikan derajat keanggotaan fuzzy untuk stok
    //     $stok_kurang = $stok_sedang = $stok_banyak = 0;
    //     if ($stok <= 50) {
    //         $stok_kurang = $this->fuzzify($stok, 0, 20, 50);
    //     } elseif ($stok <= 70) {
    //         $stok_sedang = $this->fuzzify($stok, 20, 50, 70);
    //     } else {
    //         $stok_banyak = $this->fuzzify($stok, 50, 70, 100);
    //     }

    //     // Definisikan derajat keanggotaan fuzzy untuk permintaan
    //     $permintaan_kurang = $permintaan_sedang = $permintaan_banyak = 0;
    //     if ($permintaan <= 50) {
    //         $permintaan_kurang = $this->fuzzify($permintaan, 0, 20, 50);
    //     } elseif ($permintaan <= 70) {
    //         $permintaan_sedang = $this->fuzzify($permintaan, 20, 50, 70);
    //     } else {
    //         $permintaan_banyak = $this->fuzzify($permintaan, 50, 70, 100);
    //     }

    //     // Inferensi fuzzy
    //     $hasil = max(
    //         min($stok_kurang, $permintaan_kurang),
    //         min($stok_kurang, $permintaan_sedang),
    //         min($stok_kurang, $permintaan_banyak),
    //         min($stok_sedang, $permintaan_kurang),
    //         min($stok_sedang, $permintaan_sedang),
    //         min($stok_sedang, $permintaan_banyak),
    //         min($stok_banyak, $permintaan_kurang),
    //         min($stok_banyak, $permintaan_sedang),
    //         min($stok_banyak, $permintaan_banyak)
    //     );

    //     return $hasil;
    // }

    private function getFuzzyDescription($value, $thresholds)
    {
        foreach ($thresholds as $threshold) {
            if ($value <= $threshold['max']) {
                $degree = $this->fuzzify($value, $threshold['a'], $threshold['b'], $threshold['c']);
                return ['description' => $threshold['description'], 'degree' => $degree];
            }
        }

        return ['description' => 'Undefined', 'degree' => 0];
    }

    private function determineDemandAndStock($stok, $permintaan)
    {
        $stokThresholds = [
            ['a' => 0, 'b' => 20, 'c' => 50, 'max' => 50, 'description' => 'sedikit'],
            ['a' => 20, 'b' => 50, 'c' => 70, 'max' => 70, 'description' => 'sedang'],
            ['a' => 50, 'b' => 70, 'c' => 100, 'max' => 100, 'description' => 'banyak']
        ];

        $permintaanThresholds = [
            ['a' => 0, 'b' => 20, 'c' => 40, 'max' => 40, 'description' => 'permintaan rendah'],
            ['a' => 20, 'b' => 40, 'c' => 60, 'max' => 60, 'description' => 'permintaan sedang'],
            ['a' => 40, 'b' => 60, 'c' => 100, 'max' => 100, 'description' => 'permintaan tinggi']
        ];

        $stokDesc = $this->getFuzzyDescription($stok, $stokThresholds);
        $permintaanDesc = $this->getFuzzyDescription($permintaan, $permintaanThresholds);

        $fuzzyResults = [
            min($stokDesc['degree'], $permintaanDesc['degree'])
        ];

        $hasil = max($fuzzyResults);

        return [
            'stok' => $stokDesc,
            'permintaan' => $permintaanDesc,
            'derajat_keanggotaan' => $hasil,
            'keterangan_derajat_keanggotaan' => $this->getMembershipDescription($hasil)
        ];
    }

    private function getMembershipDescription($value)
    {
        if ($value >= 0 && $value <= 0.3) {
            return 'Persediaan sangat kurang. Toko kemungkinan besar tidak dapat memenuhi permintaan. Toko perlu ningkatkan persediaan.';
        } elseif ($value > 0.3 && $value <= 0.7) {
            return 'Persediaan cukup, tetapi ada risiko toko tidak dapat memenuhi permintaan seluruhnya. Toko perlu meningkatkan persediaan.';
        } elseif ($value > 0.7 && $value <= 1) {
            return 'Persediaan memadai atau lebih dari cukup. Toko kemungkinan besar dapat memenuhi permintaan.';
        } else {
            return 'Undefined';
        }
    }

    private $u_Produksi = [];
    private $zt_Produksi = [];
    private $zs_Produksi = [];

    private function hitung_u($stok, $permintaan)
    {
        $turun = function() use ($permintaan) {
            return max(0, 1 - ($permintaan / 100)); // Contoh logika fuzzy sederhana
        };

        $naik = function() use ($permintaan) {
            return max(0, ($permintaan - 50) / 50); // Contoh logika fuzzy sederhana
        };

        $banyak = function() use ($stok) {
            return max(0, 1 - ($stok / 100)); // Contoh logika fuzzy sederhana
        };

        $sedikit = function() use ($stok) {
            return max(0, ($stok - 50) / 50); // Contoh logika fuzzy sederhana
        };

        $this->u_Produksi[0] = min($turun(), $banyak());
        $this->u_Produksi[1] = min($turun(), $sedikit());
        $this->u_Produksi[2] = min($naik(), $banyak());
        $this->u_Produksi[3] = min($naik(), $sedikit());
    }

    private function hitung_zt()
    {
        $berkurang = function ($u) {
            return 100 - (100 * $u); // Contoh logika fuzzy sederhana
        };

        $bertambah = function ($u) {
            return 50 + (50 * $u); // Contoh logika fuzzy sederhana
        };

        $this->zt_Produksi[0] = $berkurang($this->u_Produksi[0]);
        $this->zt_Produksi[1] = $berkurang($this->u_Produksi[1]);
        $this->zt_Produksi[2] = $bertambah($this->u_Produksi[2]);
        $this->zt_Produksi[3] = $bertambah($this->u_Produksi[3]);
    }

    private function hitung_zs($stok, $permintaan)
    {
        $this->zs_Produksi[0] = $permintaan - $stok;
        $this->zs_Produksi[1] = $permintaan;
        $this->zs_Produksi[2] = $permintaan;
        $this->zs_Produksi[3] = 1.25 * $permintaan - $stok;
    }

    private function bobot()
    {
        $atas_zt = 0;
        $bawah_zt = 0;
        $atas_zs = 0;
        $bawah_zs = 0;

        for ($i = 0; $i < count($this->u_Produksi); $i++) {
            $atas_zt += ($this->u_Produksi[$i] * $this->zt_Produksi[$i]);
            $bawah_zt += $this->u_Produksi[$i];
            $atas_zs += ($this->u_Produksi[$i] * $this->zs_Produksi[$i]);
            $bawah_zs += $this->u_Produksi[$i];
        }

        $t_jml_prod = $atas_zt / $bawah_zt;
        $s_jml_prod = $atas_zs / $bawah_zs;

        return [$t_jml_prod, $s_jml_prod];
    }
}
