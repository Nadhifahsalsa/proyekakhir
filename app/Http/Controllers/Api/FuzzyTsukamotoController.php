<?php

namespace App\Http\Controllers;

use App\Models\barangKeluar;
use App\Models\barangMasuk;
use App\Models\FuzzyTsukamoto;

class FuzzyController extends Controller
{
    public function index()
    {
        $fuzzy = new FuzzyTsukamoto();
        
        // Ambil data histori
        $barangMasuk = BarangMasuk::sum('jumlah_barang');
        $barangKeluar = BarangKeluar::sum('jumlah_barang');
        
        // Menghitung persediaan
        $persediaan = $barangMasuk - $barangKeluar;
        
        // Contoh permintaan (nanti diambil dari input user)
        $permintaan = 100;
        
        // Proses Fuzzy Tsukamoto
        $keanggotaanPermintaan = $fuzzy->keanggotaanPermintaan($permintaan);
        $keanggotaanPersediaan = $fuzzy->keanggotaanPersediaan($persediaan);
        $outputInferensi = $fuzzy->inferensi($keanggotaanPermintaan, $keanggotaanPersediaan);
        $hasil = $fuzzy->defuzzifikasi($outputInferensi);
        
        // Kirim hasil ke view
        return view('hasil_prediksi', compact('hasil'));
    }
}