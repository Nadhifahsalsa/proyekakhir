<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\barangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{   
    public function index()
    {
        $barangKeluarTiapBulan = barangKeluar::barangKeluarTiapBulan();

        // Ambil 5 barang yang selalu keluar setiap bulan
        $barangKeluarTiapBulan = $barangKeluarTiapBulan->take(5);

        // return view('pages.filter', $barangKeluarTiapBulan);
        //return view('pages.filter', compact('barangKeluarTiapBulan'));
        return view('pages.filter', ['barangKeluarTiapBulan' => $barangKeluarTiapBulan]);
    }


    public function exit(Request $request){
        $input = $request -> all();
        $validator  = Validator::make($input, [
            'id_barang_keluar' => 'required',
            'barang' => 'required',
            'jumlah_barang' => 'required',
            'tgl_keluar' => 'required'
        ]);
        if($validator -> fails()){
            return $this -> sendError('Validation Error.', $validator->errors());
        }
        $barangKeluar = barangKeluar::create($input);
        return response()->json([
            "success" => true, 
            "message" => "Product created successfully.",
            "data" => $barangKeluar
        ]);
    }

    public function show() {
        $barangKeluar = barangKeluar::all();
        if (is_null($barangKeluar)){
            return $this -> sendError('Product not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Product retrieved successfully.",
            "data" => $barangKeluar
        ]);
    }

    public function show_id($id) {
        $barangKeluar = barangKeluar::find($id);
        if (is_null($barangKeluar)){
            return response()->json([
            "success" => false,
            "message" => "Product not found."
        ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Product retrieved by id successfully.",
            "data" => $barangKeluar
        ]);
    }

    public function update(Request $request, $id){
        $input = $request->all();
        $barangKeluar = barangKeluar::find($id);

        if (is_null($barangKeluar)){
            return response()->json([
            "success" => false,
            "message" => "Outgoing Product ID not Found."
            ]);
        }

        $barangKeluar->id_barang_keluar = $input['id_barang_keluar'];
        $barangKeluar->barang = $input['barang'];
        $barangKeluar->jumlah_barang = $input['jumlah_barang'];
        $barangKeluar->tgl_keluar = $input['tgl_keluar'];
        $barangKeluar->save();

        return response()->json([
            "success" => true,
            "message" => "Product updated successfully.",
            "data" => $barangKeluar
        ]);
    }

    public function delete( $id){
        $barangKeluar = barangKeluar::find($id);
        if (is_null($barangKeluar)){
            return response()->json([
            "success" => false,
            "message" => "Product not Found."
            ]);
        }
        $barangKeluar -> delete();
        return response()->json([
            // $barangKeluar -> delete(),
            "success" => true,
            "message" => "Product Deleted Successfully.",
            "data" => $barangKeluar
        ]);
    }
}