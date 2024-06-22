 @extends('master')
 @section('title')
     Table
 @endsection
 @section('content')
     {{-- @dd($hasil_fuzzy) --}}
     <div class="product-status mg-b-15">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="product-status-wrap">
                         <h1>Predictions for Next Month</h1>
                         <table>

                             <thead>
                                 <tr>
                                     <th>No</th>
                                     <th>Kode Barang</th>
                                     <th>Jumlah Permintaan / Hasil Forecast</th>
                                     <th>Derajat Keanggotaan Forecast</th>
                                     <th>Keterangan Forecast</th>
                                     <th>Jumlah Stok</th>
                                     <th>Derajat Keanggotaan Stok</th>
                                     <th>Keterangan stok</th>
                                     {{-- <th>Derajat Keanggotaan</th>
                                     <th>Keterangan Derajat Keanggotaan</th> --}}
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach ($hasil_fuzzy as $index => $hasil)
                                     <tr>
                                         <td>{{ $loop->iteration }}</td>
                                         <td>{{ $hasil['barang'] }}</td>
                                         <td>{{ $hasil['forecast'] }}</td>
                                         <td>{{ $hasil['forecast_degree'] }}</td>
                                         <td>{{ $hasil['forecast_keterangan'] }}</td>
                                         <td>{{ $hasil['jumlah'] }}</td>
                                         <td>{{ $hasil['stok_degree'] }}</td>
                                         <td>{{ $hasil['stok_keterangan'] }}</td>
                                         {{-- <td>{{ $hasil_fuzzy['derajat_keanggotaan'] }}</td>
                                         <td>{{ $hasil_fuzzy['keterangan_derajat_keanggotaan'] }}</td> --}}
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                         <br><br>
                         <h3>Hasil Derajat Keanggotaann dari Forecast dan Stok</h3>
                         <table>
                             <thead>
                                 <tr>
                                     <th>No</th>
                                     <th>Kode Barang</th>
                                     <th>Derajat Keanggotaan Hasil Forecast dan Stok</th>
                                     <th>Keterangan Derajat Keanggotaan</th>
                                      <th>Jumlah Perlu Distok</th>
                                      {{-- <th>Jumlah Perlu Distok (Produksi Sugeno)</th> --}}
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach ($hasil_fuzzy as $index => $hasil)
                                     <tr>
                                         <td>{{ $loop->iteration }}</td>
                                         <td>{{ $hasil['barang'] }}</td>
                                         <td>{{ $hasil['derajat_keanggotaan'] }}</td>
                                         <td>{{ $hasil['keterangan_derajat_keanggotaan'] }}</td>
                                         <td>{{ $hasil['produksi_tsukamoto'] }}</td>
                                         {{-- <td>{{ $hasil['produksi_sugeno'] }}</td> --}}
                                     </tr>
                                 @endforeach
                             </tbody>

                         </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection
