<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class supplierController extends Controller
{
    public function index(){
        $data = [
            "data" => supplier::get(),
        ];
        return view('pages.supplier', $data);
    }

    public function insuppliers(Request $request){
        $input = $request -> all();
        $validator = Validator::make($input, [
            'kode_supplier' => 'required',
            'nama_supplier' => 'required'
        ]);
        if($validator -> fails()){
            return $this -> sendError('Validation Error.', $validator->errors());
        }
        $supplier = supplier::create($input);
        return response()->json([
            "success" => true,
            "message" => "Supplier created successfully.",
            "data" => $supplier
        ]);
    }

    public function show() {
        $supplier = supplier::all();
        if (is_null($supplier)){
            return $this -> sendError('Supplier not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Supplier retrieved successfully.",
            "data" => $supplier
        ]);
    }

    public function show_id($id) {
        $supplier = supplier::find($id);
        if (is_null($supplier)){
            return response()->json([
            "success" => false,
            "message" => "Supplier not found."
        ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Supplier retrieved by id successfully.",
            "data" => $supplier
        ]);
    }

    public function update(Request $request, $id){
        $input = $request->all();
        $supplier = supplier::find($id);

        if (is_null($supplier)){
            return response()->json([
            "success" => false,
            "message" => "Supplier not found."
            ]);
        }

        $supplier->kode_supplier = $input['kode_supplier'];
        $supplier->nama_supplier = $input['nama_supplier'];
        $supplier->save();

        return response()->json([
            "success" => true,
            "message" => "Supplier updated successfully.",
            "data" => $supplier
        ]);
    }

    public function delete( $id){
        $supplier = supplier::find($id);
        if (is_null($supplier)){
            return response()->json([
            "success" => false,
            "message" => "Supplier not found."
            ]);
        }
        $supplier -> delete();
        return response()->json([
            // $supplier -> delete(),
            "success" => true,
            "message" => "Supplier deleted successfully.",
            "data" => $supplier
        ]);
        
        // $supplier = supplier::find($id);
        // $supplier -> delete();
        // return response() -> json([
        //     "success" => true,
        //     "message" => "Supplier deleted successfully.",
        //     "data" => $supplier
        // ]);
    }
}
