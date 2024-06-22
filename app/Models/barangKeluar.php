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

    //protected $table = 'barang_keluars';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // public static function barangKeluarTiapBulan()
    // {
    //     return self::select('barangs.nama_barang', DB::raw('COUNT(DISTINCT EXTRACT(MONTH FROM tgl_keluar)) as jumlah_bulan'))
    //         ->join('barangs', 'barang_keluars.id_barang_keluar', '=', 'barangs.id')
    //         ->groupBy('barangs.nama_barang')
    //         ->havingRaw('COUNT(DISTINCT EXTRACT(MONTH FROM tgl_keluar)) = 12')
    //         ->get();
    // }
    
    
    
    // public static function barangKeluarTiapBulan()
    // {
    //     return self::select('barang', DB::raw('COUNT(DISTINCT EXTRACT(MONTH FROM tgl_keluar)) as jumlah_bulan'))
    //         ->groupBy('barang')
    //         ->havingRaw('COUNT(DISTINCT EXTRACT(MONTH FROM tgl_keluar)) = 12')
    //         ->get();
    // }
}
