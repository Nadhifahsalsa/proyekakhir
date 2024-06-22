<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_barang', 'supplier_id', 'nama_barang'
    ];

    protected $table = 'barangs';

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'barang', 'id');
    }
}
