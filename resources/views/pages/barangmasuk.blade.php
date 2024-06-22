 @extends('master')
 @section('title')
     Table
 @endsection
 @section('content')
     <div class="product-status mg-b-15">
         <div class="container-fluid">
             {{-- <h1>Daftar Barang Masuk</h1> --}}
             @if (session('success'))
                 <div class="alert alert-success">
                     {{ session('success') }}
                 </div>
             @endif
             @if (session('error'))
                 <div class="alert alert-danger">
                     {{ session('error') }}
                 </div>
             @endif
             <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="product-status-wrap">
                         {{-- <h4>Library List</h4>
                         <div class="add-product">
                             <a href="#">Add Library</a>
                         </div> --}}
                         <div class="asset-inner">
                             <h1>Daftar Barang Masuk</h1>
                             <table>
                                 <thead>
                                     <tr>
                                         {{-- <th>NO</th> --}}
                                         <th>ID Barang Masuk</th>
                                         <th>Kode Barang</th>
                                         <th>Nama Barang</th>
                                         <th>Jumlah Barang</th>
                                         <th>Tanggal Masuk</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach ($data as $data)
                                         <tr>
                                             {{-- <td>{{ $loop->iteration }}</td> --}}
                                             <td>{{ $data->id }}</td>
                                             <td>{{ $data->barang }}</td>
                                             <td>{{ $data->nama_barang }}</td>
                                             <td>{{ $data->jumlah_barang }}</td>
                                             <td>{{ $data->tgl_masuk }}</td>
                                             <td>
                                                 <a href="{{ route('barangMasuk.edit', $data->id) }}"
                                                     class="btn btn-warning">Edit</a>
                                                 <form action="{{ route('barangMasuk.destroy', $data->id) }}" method="POST"
                                                     style="display:inline-block;">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="btn btn-danger"
                                                         onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
                                                 </form>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                         {{-- <div class="custom-pagination">
                             <ul class="pagination">
                                 <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                 <li class="page-item"><a class="page-link" href="#">1</a></li>
                                 <li class="page-item"><a class="page-link" href="#">2</a></li>
                                 <li class="page-item"><a class="page-link" href="#">3</a></li>
                                 <li class="page-item"><a class="page-link" href="#">Next</a></li>
                             </ul>
                         </div> --}}
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection
