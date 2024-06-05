 @extends('master')
 @section('title')
     Dashboard
 @endsection
 @section('content')
     {{-- <div class="analytics-sparkle-area">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="analytics-sparkle-line reso-mg-b-30">
                         <div class="analytics-content">
                             <h5>Computer Technologies</h5>
                             <h2>$<span class="counter">5000</span> <span class="tuition-fees">Tuition Fees</span></h2>
                             <span class="text-success">20%</span>
                             <div class="progress m-b-0">
                                 <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100" style="width:20%;"> 
                                     <span class="sr-only">20%Complete</span> </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="analytics-sparkle-line reso-mg-b-30">
                         <div class="analytics-content">
                             <h5>Accounting Technologies</h5>
                             <h2>$<span class="counter">3000</span> <span class="tuition-fees">Tuition Fees</span></h2>
                             <span class="text-danger">30%</span>
                             <div class="progress m-b-0">
                                 <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100" style="width:30%;"> <span class="sr-only">230%
                                         Complete</span> </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="analytics-sparkle-line reso-mg-b-30 table-mg-t-pro dk-res-t-pro-30">
                         <div class="analytics-content">
                             <h5>Electrical Engineering</h5>
                             <h2>$<span class="counter">2000</span> <span class="tuition-fees">Tuition Fees</span></h2>
                             <span class="text-info">60%</span>
                             <div class="progress m-b-0">
                                 <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100" style="width:60%;"> <span class="sr-only">20%
                                         Complete</span> </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="analytics-sparkle-line table-mg-t-pro dk-res-t-pro-30">
                         <div class="analytics-content">
                             <h5>Chemical Engineering</h5>
                             <h2>$<span class="counter">3500</span> <span class="tuition-fees">Tuition Fees</span></h2>
                             <span class="text-inverse">80%</span>
                             <div class="progress m-b-0">
                                 <div class="progress-bar progress-bar-inverse" role="progressbar" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100" style="width:80%;"> <span class="sr-only">230%
                                         Complete</span> </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div> --}}
     <div class="product-sales-area mg-tb-30">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="product-sales-chart">
                         <div class="portlet-title">
                            
                            <span class="caption-subject"><b>Penjualan</b></span>
                         </div>
                         <ul class="list-inline cus-product-sl-rp">
                             <li>
                                 <h5><i class="fa fa-circle" style="color: #006DF0;"></i>MY JELLY 5CUPS-60</h5>
                             </li>
                             <li>
                                 <h5><i class="fa fa-circle" style="color: #933EC5;"></i>MY JELLY 80GX3-24</h5>
                             </li>
                             <li>
                                 <h5><i class="fa fa-circle" style="color: #65b12d;"></i>MY JELLY 5CUPS-60</h5>
                             </li>
                         </ul>
                         <div id="extra-area-chart" style="height: 356px;"></div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="traffice-source-area mg-b-30">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="white-box analytics-info-cs">
                         <h3 class="box-title">Total Visit</h3>
                         <ul class="list-inline two-part-sp">
                             <li>
                                 <div id="sparklinedash"></div>
                             </li>
                             <li class="text-right sp-cn-r"><i class="fa fa-level-up" aria-hidden="true"></i> <span
                                     class="counter text-success"><span class="counter">1500</span></span>
                             </li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="white-box analytics-info-cs res-mg-t-30 table-mg-t-pro-n">
                         <h3 class="box-title">Page Views</h3>
                         <ul class="list-inline two-part-sp">
                             <li>
                                 <div id="sparklinedash2"></div>
                             </li>
                             <li class="text-right graph-two-ctn"><i class="fa fa-level-up" aria-hidden="true"></i>
                                 <span class="counter text-purple"><span class="counter">3000</span></span>
                             </li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="white-box analytics-info-cs res-mg-t-30 res-tablet-mg-t-30 dk-res-t-pro-30">
                         <h3 class="box-title">Unique Visitor</h3>
                         <ul class="list-inline two-part-sp">
                             <li>
                                 <div id="sparklinedash3"></div>
                             </li>
                             <li class="text-right graph-three-ctn"><i class="fa fa-level-up" aria-hidden="true"></i>
                                 <span class="counter text-info"><span class="counter">5000</span></span>
                             </li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                     <div class="white-box analytics-info-cs res-mg-t-30 res-tablet-mg-t-30 dk-res-t-pro-30">
                         <h3 class="box-title">Bounce Rate</h3>
                         <ul class="list-inline two-part-sp">
                             <li>
                                 <div id="sparklinedash4"></div>
                             </li>
                             <li class="text-right graph-four-ctn"><i class="fa fa-level-down" aria-hidden="true"></i>
                                 <span class="text-danger"><span class="counter">18</span>%</span>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="product-sales-area mg-tb-30">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="product-sales-chart">
                         <div class="portlet-title">
                             <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                     <div class="caption pro-sl-hd">
                                         <span class="caption-subject"><b>Adminsion Statistic</b></span>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                     <div class="actions graph-rp actions-graph-rp">
                                         <a href="#" class="btn btn-dark btn-circle active tip-top"
                                             data-toggle="tooltip" title="Refresh">
                                             <i class="fa fa-reply" aria-hidden="true"></i>
                                         </a>
                                         <a href="#" class="btn btn-blue-grey btn-circle active tip-top"
                                             data-toggle="tooltip" title="Delete">
                                             <i class="fa fa-trash-o" aria-hidden="true"></i>
                                         </a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <ul class="list-inline cus-product-sl-rp">
                             <li>
                                 <h5><i class="fa fa-circle" style="color: #006DF0;"></i>Python</h5>
                             </li>
                             <li>
                                 <h5><i class="fa fa-circle" style="color: #933EC5;"></i>PHP</h5>
                             </li>
                             <li>
                                 <h5><i class="fa fa-circle" style="color: #65b12d;"></i>Java</h5>
                             </li>
                         </ul>
                         <div id="morris-area-chart"></div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="courses-area mg-b-15">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                     <div class="white-box">
                         <h3 class="box-title">Browser Status</h3>
                         <ul class="basic-list">
                             <li>Google Chrome <span class="pull-right label-danger label-1 label">95.8%</span></li>
                             <li>Mozila Firefox <span class="pull-right label-purple label-2 label">85.8%</span></li>
                             <li>Apple Safari <span class="pull-right label-success label-3 label">23.8%</span></li>
                             <li>Internet Explorer <span class="pull-right label-info label-4 label">55.8%</span></li>
                             <li>Opera mini <span class="pull-right label-warning label-5 label">28.8%</span></li>
                             <li>Mozila Firefox <span class="pull-right label-purple label-6 label">26.8%</span></li>
                             <li>Safari <span class="pull-right label-purple label-7 label">31.8%</span></li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                     <div class="white-box res-mg-t-30 table-mg-t-pro-n">
                         <h3 class="box-title">Visits from countries</h3>
                         <ul class="country-state">
                             <li>
                                 <h2><span class="counter">1250</span></h2> <small>From Australia</small>
                                 <div class="pull-right">75% <i class="fa fa-level-up text-danger ctn-ic-1"></i></div>
                                 <div class="progress">
                                     <div class="progress-bar progress-bar-danger ctn-vs-1" role="progressbar"
                                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                                         <span class="sr-only">75% Complete</span>
                                     </div>
                                 </div>
                             </li>
                             <li>
                                 <h2><span class="counter">1050</span></h2> <small>From USA</small>
                                 <div class="pull-right">48% <i class="fa fa-level-up text-success ctn-ic-2"></i>
                                 </div>
                                 <div class="progress">
                                     <div class="progress-bar progress-bar-info ctn-vs-2" role="progressbar"
                                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:48%;">
                                         <span class="sr-only">48% Complete</span>
                                     </div>
                                 </div>
                             </li>
                             <li>
                                 <h2><span class="counter">6350</span></h2> <small>From Canada</small>
                                 <div class="pull-right">55% <i class="fa fa-level-up text-success ctn-ic-3"></i>
                                 </div>
                                 <div class="progress">
                                     <div class="progress-bar progress-bar-success ctn-vs-3" role="progressbar"
                                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:55%;">
                                         <span class="sr-only">55% Complete</span>
                                     </div>
                                 </div>
                             </li>
                             <li>
                                 <h2><span class="counter">950</span></h2> <small>From India</small>
                                 <div class="pull-right">33% <i class="fa fa-level-down text-success ctn-ic-4"></i>
                                 </div>
                                 <div class="progress">
                                     <div class="progress-bar progress-bar-success ctn-vs-4" role="progressbar"
                                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:33%;">
                                         <span class="sr-only">33% Complete</span>
                                     </div>
                                 </div>
                             </li>
                             <li>
                                 <h2><span class="counter">3250</span></h2> <small>From Bangladesh</small>
                                 <div class="pull-right">60% <i class="fa fa-level-up text-success ctn-ic-5"></i>
                                 </div>
                                 <div class="progress">
                                     <div class="progress-bar progress-bar-inverse ctn-vs-5" role="progressbar"
                                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:60%;">
                                         <span class="sr-only">60% Complete</span>
                                     </div>
                                 </div>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection
