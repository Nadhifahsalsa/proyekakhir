<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'id_barang_masuk', 
        'barang', 
        'jumlah_barang', 
        'tgl_masuk'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang', 'id');
    }
}
