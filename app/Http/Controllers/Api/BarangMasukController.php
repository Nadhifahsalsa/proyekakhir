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
    // public function index(){
    //     $data = [
    //         "data" => barangMasuk::get(),
    //     ];
    //     return view('pages.barangmasuk', $data);
    // }

    public function index()
    {
        // $barangMasuk = DB::table('barang_masuks')
        // ->join('barangs', 'barang_masuks.barang', '=', 'barangs.kode_barang')
        // ->select('barang_masuks.*', 'barangs.nama_barang')
        // ->get();

        // $data = DB::table('barang_masuks')
        // ->join('barangs', 'barang_masuks.barang', '=', DB::raw('CAST(barangs.id AS BIGINT)'))
        // ->select('barang_masuks.id','barang_masuks.barang', 
        // 'barang_masuks.jumlah_barang', 'barang_masuks.tgl_masuk', 'barangs.nama_barang')
        // ->get();

        $data = DB::table('barang_masuks')
            ->join('barangs', 'barang_masuks.barang', '=', DB::raw('CAST(barangs.id AS BIGINT)'))
            ->select(
                'barang_masuks.id',
                'barang_masuks.barang',
                'barang_masuks.jumlah_barang',
                'barang_masuks.tgl_masuk',
                'barangs.nama_barang'
            )
            ->get();
        // $data = DB::table('barang_masuks')
        // ->join('barangs', 'barang_masuks.barang', '=', DB::raw('CAST(barangs.kode_barang AS BIGINT)'))
        // ->select('barang_masuks.*', 'barangs.*')
        // ->get();

        // dd($data); // Debugging
        // return view('pages.barangmasuk', $data);
        return view('pages.barangmasuk', ['data' => $data]);
    }


    public function create()
    {
        $barangs = Barang::all();
        // dd($barangs);
        return view('pages.create_barangmasuk', ['barangs' => $barangs]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id_barang_masuk' => 'required',
            'barang' => 'required|exists:barangs,id',
            'jumlah_barang' => 'required',
            'tgl_masuk' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->route('barangMasuk.create')->withErrors($validator)->withInput();
        }
        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $validator->errors()
        //     ], 400);
        // }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Barang Masuk berhasil ditambahkan',
        //     'data' => $barangMasuk
        // ]);
        // barangMasuk::create($request->all());

        // Jangan masukkan 'id' secara manual
        barangMasuk::create($request->only('barang', 'jumlah_barang', 'tgl_masuk'));
        return redirect()->route('barangMasuk.index')->with('success', 'Barang Masuk berhasil ditambahkan');
        // return redirect()->route('barangMasuk.index')->with('success', 'Barang Masuk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barangMasuk = barangMasuk::find($id);
        if (!$barangMasuk) {
            return redirect()->route('barangmasuk.index')->with('error', 'Data not found');
        }
        $barangs = Barang::all(); // Mengambil semua data barang untuk dropdown
        return view('pages.edit_barangmasuk', compact('barangMasuk', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'id_barang_masuk' => 'required',
            'barang' => 'required|exists:barangs,id',
            'jumlah_barang' => 'required',
            'tgl_masuk' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->route('barangMasuk.edit', $id)->withErrors($validator)->withInput();
        }

        $barangMasuk = barangMasuk::find($id);
        if (!$barangMasuk) {
            return redirect()->route('barangMasuk.index')->with('error', 'Data tidak ditemukan');
        }

        // $barangMasuk->update($request->all());
        $barangMasuk->update($request->only('barang', 'jumlah_barang', 'tgl_masuk'));
        return redirect()->route('barangMasuk.index')->with('success', 'Barang Masuk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barangMasuk = barangMasuk::find($id);
        if (!$barangMasuk) {
            return redirect()->route('barangMasuk.index')->with('error', 'Data tidak ditemukan');
        }

        $barangMasuk->delete();
        return redirect()->route('barangMasuk.index')->with('success', 'Barang Masuk berhasil dihapus');
    }
}
