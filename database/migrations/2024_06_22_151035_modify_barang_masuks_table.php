<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            // Hapus kolom 'id_barang_masuk' jika ada
            if (Schema::hasColumn('barang_masuks', 'id_barang_masuk')) {
                $table->dropColumn('id_barang_masuk');
            }

            // Tambah kolom 'id' jika belum ada
            if (!Schema::hasColumn('barang_masuks', 'id')) {
                $table->id()->first();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            // Tambah kembali kolom 'id_barang_masuk' jika perlu
            if (!Schema::hasColumn('barang_masuks', 'id_barang_masuk')) {
                $table->string('id_barang_masuk')->unique();
            }

            // Hapus kolom 'id' jika perlu
            if (Schema::hasColumn('barang_masuks', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
};
