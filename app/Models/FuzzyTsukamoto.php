<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuzzyTsukamoto extends Model
{
    use HasFactory;
    public function keanggotaanPermintaan($permintaan)
    {
        // Logika untuk menghitung nilai keanggotaan permintaan
        if ($permintaan <= 50) {
            return 1; // Permintaan rendah
        } elseif ($permintaan <= 100) {
            return (100 - $permintaan) / 50; // Permintaan menengah
        } else {
            return 0; // Permintaan tinggi
        }
    }

    public function keanggotaanPersediaan($persediaan)
    {
        // Logika untuk menghitung nilai keanggotaan persediaan
        if ($persediaan <= 20) {
            return 1; // Persediaan rendah
        } elseif ($persediaan <= 50) {
            return (50 - $persediaan) / 30; // Persediaan menengah
        } else {
            return 0; // Persediaan tinggi
        }
    }

    public function inferensi($permintaan, $persediaan)
    {
        // Logika untuk inferensi berdasarkan nilai keanggotaan
        $Permintaan = $this->keanggotaanPermintaan($permintaan);
        $Persediaan = $this->keanggotaanPersediaan($persediaan);

        // Aturan inferensi sederhana
        if ($Permintaan > 50 && $Persediaan > 50) {
            return "Kemungkinan memenuhi permintaan tinggi";
        } else {
            return "Kemungkinan memenuhi permintaan rendah";
        }
    }

    public function defuzzifikasi($outputInferensi)
    {
        // Logika untuk defuzzifikasi
        if ($outputInferensi == "Kemungkinan memenuhi permintaan tinggi") {
            return 75; // Persentase kemungkinan memenuhi permintaan
        } else {
            return 25;
        }
    }
}
