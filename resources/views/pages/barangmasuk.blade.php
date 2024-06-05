 @extends('master')
 @section('title')
     Table
 @endsection
 @section('content')
     <div class="product-status mg-b-15">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="product-status-wrap">
                         {{-- <h4>Library List</h4>
                         <div class="add-product">
                             <a href="#">Add Library</a>
                         </div> --}}
                         <div class="asset-inner">
                             <table>
                                 <tr>
                                     <th>NO</th>
                                     <th>ID Barang Masuk</th>
                                     <th>Nama Barang</th>
                                     <th>Jumlah Barang</th>
                                     <th>Tanggal Masuk</th>
                                 </tr>
                                 @foreach ($data as $item)
                                     <tr>
                                         <td>{{ $item->id_barang_masuk }}</td>
                                         <td>{{ $item->nama_barang }}</td>
                                         <td>{{ $item->jumlah_barang }}</td>
                                         <td>{{ $item->tgl_masuk }}</td>
                                     </tr>
                                 @endforeach
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
