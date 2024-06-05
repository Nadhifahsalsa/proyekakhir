<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\barangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(){
        $data = [
            "data" => barangMasuk::get(),
        ];
        return view('pages.barangmasuk', $data);
    }

    // public function showBarangMasuk(){
    //     $barangMasuk = DB::table('barangMasuk')
    //             ->join('barangs', 'barang_masuk.id_barang', '=', 'barangs.id')
    //             ->select('barangMasuk.*', 'barangs.nama_barang')
    //             ->get();

    //             // dd($barangMasuk);

    //     return view('pages.barangmasuk', compact('barangMasuk'));
    // }



    public function entry(Request $request){
        $input = $request -> all();
        $validator  = Validator::make($input, [
            'id_barang_masuk'=> 'required',
            'barang' => 'required',
            'jumlah_barang' => 'required',
            'tgl_masuk' => 'required'
        ]);
        if($validator -> fails()){
            return $this -> sendError('Validation Error.', $validator->errors());
        }
        $barangMasuk = barangMasuk::create($input);
        return response()->json([
            "success" => true, 
            "message" => "Product created successfully.",
            "data" => $barangMasuk
        ]);
    }

    public function show() {
        $barangMasuk = barangMasuk::all();
        if (is_null($barangMasuk)){
            return $this -> sendError('Product not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Product retrieved successfully.",
            "data" => $barangMasuk
        ]);
    }

    public function show_id($id) {
        $barangMasuk = barangMasuk::find($id);
        if (is_null($barangMasuk)){
            return response()->json([
            "success" => false,
            "message" => "Product not found."
        ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Product retrieved by id successfully.",
            "data" => $barangMasuk
        ]);
    }

    public function update(Request $request, $id){
        $input = $request->all();
        $barangMasuk = barangMasuk::find($id);

        if (is_null($barangMasuk)){
            return response()->json([
            "success" => false,
            "message" => "Incoming Product ID not Found."
            ]);
        }

        $barangMasuk->id_barang_masuk = $input['id_barang_masuk'];
        $barangMasuk->barang = $input['barang'];
        $barangMasuk->jumlah_barang = $input['jumlah_barang'];
        $barangMasuk->tgl_masuk = $input['tgl_masuk'];
        $barangMasuk->save();

        return response()->json([
            "success" => true,
            "message" => "Product updated successfully.",
            "data" => $barangMasuk
        ]);
    }

    public function delete( $id){
        $barangMasuk = barangMasuk::find($id);
        if (is_null($barangMasuk)){
            return response()->json([
            "success" => false,
            "message" => "Product not Found."
            ]);
        }
        $barangMasuk -> delete();
        return response()->json([
            // $barangMasuk -> delete(),
            "success" => true,
            "message" => "Product Deleted Successfully.",
            "data" => $barangMasuk
        ]);
    }
}
