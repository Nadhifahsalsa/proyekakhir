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

class ForecastController extends Controller
{
    public function predict(Request $request)
    {
        // Ambil data barang keluar dari database
        $data = DB::table('barang_keluar')->select('date', 'quantity')->get()->toArray();

        // Konversi data ke format JSON
        $jsonData = json_encode($data);

        // Path ke skrip Python
        $pythonScriptPath = base_path('python_scripts/arima_script.py');

        // Menjalankan skrip Python
        $process = new Process(['python', $pythonScriptPath, $jsonData]);
        $process->run();

        // Mengecek apakah proses berhasil
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Mengambil output dari skrip Python
        $output = $process->getOutput();
        $forecast = json_decode($output, true);

        return response()->json($forecast);
    }
}
