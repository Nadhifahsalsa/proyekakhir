<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
  
    public function store(Request $request){
        $input = $request -> all();
        $validator  = Validator::make($input, [
            'kode_barang' => 'required',
            'supplier_id' => 'required',
            'nama_barang' => 'required'
        ]);
        if($validator -> fails()){
            return $this -> sendError('Validation Error.', $validator->errors());
        }
        $barang = barang::create($input);
        return response()->json([
            "success" => true, 
            "message" => "Product created successfully.",
            "data" => $barang
        ]);
    }

    public function show() {
        $barang = barang::all();
        if (is_null($barang)){
            return $this -> sendError('Product not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Product retrieved successfully.",
            "data" => $barang
        ]);
    }

    public function show_id($id) {
        $barang = barang::find($id);
        if (is_null($barang)){
            return response()->json([
            "success" => false,
            "message" => "Product not found."
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Product retrieved by id successfully.",
            "data" => $barang
        ]);
    }

    public function update(Request $request, $id){
        $input = $request->all();
        $barang = barang::find($id);

        if (is_null($barang)){
            return response()->json([
            "success" => false,
            "message" => "Product not found."
            ]);
        }

        $barang->kode_barang = $input['kode_barang'];
        $barang->supplier_id = $input['supplier_id'];
        $barang->nama_barang = $input['nama_barang'];
        $barang->save();

        return response()->json([
            "success" => true,
            "message" => "Product updated successfully.",
            "data" => $barang
        ]);
    }

    public function delete( $id){
        $barang = barang::find($id);
        if (is_null($barang)){
            return response()->json([
            "success" => false,
            "message" => "Product not found."
            ]);
        }
        $barang -> delete();
        return response()->json([
            // $barang -> delete(),
            "success" => true,
            "message" => "Product deleted successfully.",
            "data" => $barang
        ]);
    }
}
