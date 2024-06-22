@extends('master')
@section('title', 'Edit Barang Masuk')
@section('content')
    <div class="container">
        <h1>Edit Barang Masuk</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('barangMasuk.update', $barangMasuk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="barang_id">Barang:</label>
                <select class="form-control" id="barang" name="barang" required>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" {{ $barangMasuk->barang_id == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }} (ID: {{ $barang->id }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="jumlah_barang">Jumlah Barang:</label>
                <input type="text" class="form-control" id="jumlah_barang" name="jumlah_barang" value="{{ $barangMasuk->jumlah_barang }}" required>
            </div>
            <div class="form-group">
                <label for="tgl_masuk">Tanggal Masuk:</label>
                <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ $barangMasuk->tgl_masuk }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
