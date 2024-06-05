<?php

use App\Http\Controllers\Api\BarangKeluarController;
use App\Http\Controllers\Api\BarangMasukController;
use App\Http\Controllers\Api\supplierController;
use App\Http\Controllers\Api\FuzzyTsukamotoController;
use App\Models\barangMasuk;
use App\Models\FuzzyTsukamoto;
use Illuminate\Support\Facades\Route;

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


Route::get('/supplier', [supplierController::class, 'index'])->name('supplier.index');

Route::get('/barangmasuk', [BarangMasukController::class, 'index'])->name('barangMasuk.index');

//Route::get('/viewfuzzy', [FuzzyTsukamotoController::class, 'index'])->name('barangMasuk.index');

//Route::get('/barang-keluar', [BarangKeluarController::class, 'index']);