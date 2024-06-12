<?php

namespace App\Http\Controllers;

use App\Models\barangKeluar;
use App\Models\barangMasuk;
use App\Models\FuzzyTsukamoto;
use Illuminate\Support\Facades\DB;

class FuzzyController extends Controller
{
    public function index()
    {
        // Menghitung persediaan per jenis barang
        $persediaan = $this->hitungPersediaan();

        // Prediksi permintaan menggunakan ARIMA
        $prediksi_permintaan = $this->forecastARIMA($persediaan);

        // Logika Fuzzy Tsukamoto
        $hasil_fuzzy = [];
        foreach ($persediaan as $barang => $stok) {
            $permintaan = $prediksi_permintaan[$barang] ?? 0;
            $hasil_fuzzy[$barang] = $this->fuzzyTsukamoto($stok, $permintaan);
        }

        return view('pages.filter ', compact('hasil_fuzzy'));
    }

    private function hitungPersediaan()
    {
        $barang_keluar = BarangKeluar::select('barang', DB::raw('SUM(jumlah) as total_keluar'))
            ->groupBy('barang')
            ->get();
            //->keyBy('nama_barang');

        $barang_masuk = BarangMasuk::select('barang', DB::raw('SUM(jumlah) as total_masuk'))
            ->groupBy('barang')
            ->get();
            //->keyBy('nama_barang');

        $persediaan = [];
        foreach ($barang_masuk as $barang => $masuk) {
            $keluar = $barang_keluar[$barang]->total_keluar ?? 0;
            $persediaan[$barang] = $masuk->total_masuk - $keluar;
        }

        return $persediaan;
    }

    private function fuzzyTsukamoto($stok, $permintaan)
    {
        // Definisikan derajat keanggotaan fuzzy untuk stok
        $stok_kurang = $this->fuzzify($stok, 0, 100, 200);
        $stok_sedang = $this->fuzzify($stok, 100, 200, 300);
        $stok_banyak = $this->fuzzify($stok, 200, 300, 400);

        // Definisikan derajat keanggotaan fuzzy untuk permintaan
        $permintaan_kurang = $this->fuzzify($permintaan, 0, 50, 100);
        $permintaan_sedang = $this->fuzzify($permintaan, 50, 100, 150);
        $permintaan_banyak = $this->fuzzify($permintaan, 100, 150, 200);

        // Inferensi fuzzy
        $hasil = min($stok_sedang, $permintaan_banyak);

        return $hasil;
    }

    private function fuzzify($value, $a, $b, $c)
    {
        if ($value <= $a) return 0;
        if ($value >= $c) return 0;
        if ($value == $b) return 1;
        if ($value > $a && $value < $b) return ($value - $a) / ($b - $a);
        if ($value > $b && $value < $c) return ($c - $value) / ($c - $b);
        return 0;
    }


    // public function index()
    // {
    //     $fuzzy = new FuzzyTsukamoto();
        
    //     // Ambil data histori
    //     $barangMasuk = BarangMasuk::sum('jumlah_barang');
    //     $barangKeluar = BarangKeluar::sum('jumlah_barang');
        
    //     // Menghitung persediaan
    //     $persediaan = $barangMasuk - $barangKeluar;
        
    //     // Contoh permintaan (nanti diambil dari input user)
    //     $permintaan = 100;
        
    //     // Proses Fuzzy Tsukamoto
    //     $keanggotaanPermintaan = $fuzzy->keanggotaanPermintaan($permintaan);
    //     $keanggotaanPersediaan = $fuzzy->keanggotaanPersediaan($persediaan);
    //     $outputInferensi = $fuzzy->inferensi($keanggotaanPermintaan, $keanggotaanPersediaan);
    //     $hasil = $fuzzy->defuzzifikasi($outputInferensi);
        
    //     // Kirim hasil ke view
    //     return view('hasil_prediksi', compact('hasil'));
    // }
}