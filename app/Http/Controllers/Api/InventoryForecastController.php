<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\barangKeluar;
use App\Models\BarangMasuk;
use Phpml\TimeSeries\Arima;
use Illuminate\Http\Request;

class InventoryForecastController extends Controller
{
    public function index()
    {
        // Mengambil data jumlah barang keluar
        $barangKeluarData = barangKeluar::select('jumlah')->get()->pluck('jumlah')->toArray();

        // Menggunakan ARIMA untuk memprediksi kebutuhan barang keluar
        $arima = new Arima($order = [1, 1, 1], $seasonalOrder = [1, 1, 1, 3], $period = 3);
        $arima->train($barangKeluarData);
        $predictions = $arima->forecast(count($barangKeluarData), 3); // Prediksi untuk 3 periode ke depan

        return view('inventory_forecast', ['predictions' => $predictions]);
    }
}
