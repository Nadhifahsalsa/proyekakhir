<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\BarangKeluarController;
use App\Http\Controllers\Api\BarangMasukController;
use App\Http\Controllers\Api\supplierController;
use App\Http\Controllers\Api\FuzzyTsukamotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/prediksi', 'FuzzyTsukamotoController@predict');

Route::get('/barang_keluar', [BarangKeluarController::class, 'index']);

// Route::post('supplier', [supplierController::class,'insuppliers']);
// Route::get('supplier', [SupplierController::class,'show']);
// Route::put('supplier/{id}', [SupplierController::class,'update']);
// Route::delete('supplier/{id}', [SupplierController::class,'delete']);
// Route::get('supplier/{id}', [SupplierController::class,'show_id']);

Route::post('barang', [BarangController::class,'store']);
Route::get('barang', [BarangController::class,'show']);
Route::put('barang/{id}', [BarangController::class,'update']);
Route::delete('barang/{id}', [BarangController::class,'delete']);
Route::get('barang/{id}', [BarangController::class,'show_id']);

Route::post('barangMasuk', [BarangMasukController::class,'entry']);
Route::get('barangMasuk', [BarangMasukController::class,'show']);
Route::put('barangMasuk/{id}', [BarangMasukController::class,'update']);
Route::delete('barangMasuk/{id}', [BarangMasukController::class,'delete']);
Route::get('barangMasuk/{id}', [BarangMasukController::class,'show_id']);

Route::post('barangKeluar', [BarangKeluarController::class,'exit']);
Route::get('barangKeluar', [BarangKeluarController::class,'show']);
Route::put('barangKeluar/{id}', [BarangKeluarController::class,'update']);
Route::delete('barangKeluar/{id}', [BarangKeluarController::class,'delete']);
Route::get('barangKeluar/{id}', [BarangKeluarController::class,'show_id']);