<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\barangKeluar;
use App\Models\barangMasuk;
use Illuminate\Http\Request;
use Forecast;

class GrafikController extends Controller
{
    public function index()
    {
        $penjualanData = barangKeluar::select('jumlah')->get()->pluck('jumlah')->toArray();
        $pembelianData = barangMasuk::select('jumlah')->get()->pluck('jumlah')->toArray();

        // Menggunakan library Forecast untuk ARMA
        // $forecast = new Forecast();
        // $predictions = $forecast->arma($penjualanData);

        return view(compact('penjualanData', 'pembelianData'));
    }
}
