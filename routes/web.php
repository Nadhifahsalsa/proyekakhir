<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\BarangKeluarController;
use App\Http\Controllers\Api\BarangMasukController;
use App\Http\Controllers\Api\supplierController;
use App\Http\Controllers\Api\FuzzyTsukamotoController;
use App\Models\barangMasuk;
use App\Models\FuzzyTsukamoto;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ForecastController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/viewfuzzy', function () {
    return view('pages.viewfuzzy');
});

Route::get('/filter', function () {
    return view('pages.filter');
});


Route::get('/filter', [ForecastController::class, 'index']);

Route::get('/supplier', [supplierController::class, 'index'])->name('supplier.index');

// Route::get('/barangmasuk', [BarangMasukController::class, 'index']);

Route::get('/barangmasuk', [BarangMasukController::class, 'index'])->name('barangMasuk.index');

Route::get('/barangmasuk/create', [BarangMasukController::class, 'create'])->name('barangMasuk.create');
Route::post('/barangmasuk', [BarangMasukController::class, 'store'])->name('barangMasuk.store');
Route::get('/barangmasuk/{id}/edit', [BarangMasukController::class, 'edit'])->name('barangMasuk.edit');
Route::put('/barangmasuk/{id}', [BarangMasukController::class, 'update'])->name('barangMasuk.update');
Route::delete('/barangmasuk/{id}', [BarangMasukController::class, 'destroy'])->name('barangMasuk.destroy');

//Route::get('/viewfuzzy', [FuzzyTsukamotoController::class, 'index'])->name('barangMasuk.index');

Route::get('/barangkeluar', [BarangKeluarController::class, 'index'])->name('barangKeluar.index');

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

Route::resource('barang_masuks', BarangMasukController::class);