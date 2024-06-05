<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class barangKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_barang_keluar', 'barang', 'jumlah_barang', 'tgl_keluar'
    ];

    protected $table = 'barang_keluar';
    public static function barangKeluarTiapBulan()
    {
        return self::select('barang', DB::raw('COUNT(DISTINCT MONTH(tgl_keluar)) as jumlah_bulan'))
            ->groupBy('barang')
            ->havingRaw('COUNT(DISTINCT MONTH(tgl_keluar)) = 12')
            ->get();
    }
}
