@extends("backoffice/layout/components")



@section('top1') Dashboard @endsection



@section('top2') home @endsection



@section('top3') Dashboard detail @endsection



@section('title') Backoffice Sneekdeal @endsection

<?php

$active = "dashboard";

?>



@section('contents')

<div class="row">

    <!-- Left col -->

    <section class="col-lg-12 ">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3" data-toggle="tooltip" title="จำนวน Voucher ในระบบทั้งหมด">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-gift"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Voucher </span>
                        <span class="info-box-number">
                            {{ $voucher }}
                            <small></small>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3" data-toggle="tooltip" title="จำนวน Blog ในระบบทั้งหมด">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-list"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Blog</span>
                        <span class="info-box-number">{{ number_format($blog,0,"",",") }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3" data-toggle="tooltip" title="จำนวนรายการสินค้าที่เกิดขึ่้น ในระบบทั้งหมด">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fa fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sales</span>
                        <span class="info-box-number">{{ number_format($order,0,"",",") }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3" data-toggle="tooltip" title="สมาชิกทั้งหมด ในระบบ">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Members</span>
                        <span class="info-box-number">{{ number_format($member,0,"",",") }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <div class="row">
            <!-- Custom tabs (Charts with tabs)-->

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Sales</h3>
                            {{--<a href="javascript:void(0);">View Report</a>--}}
                        </div>
                        <div class="row">
                            {{-- {!! inputText('เลือกวัน', 'datepickerDay', 'datepickerDay', '', 'md-4', 'readonly', '') !!} --}}
                            <div class="col-md-12">
                                <div class="card-tools" style="float: right;margin-top:26px">
                                    <input type="text" value="{{($grapSales == 'D' ? date('d-m-Y'):(mb_strlen($grapSales)>3?$grapSales:'') ) }}" name="datepickerDay" id="datepickerDay" class="" readonly="" placeholder="Select Day">
                                    <button type="button" class="btn btn-{{($grapSales == 'D' ?'warning':'success')}}" onclick="window.location='/backoffice/dashboard?grapSales=D'">This Day
                                    </button>
                                    <button type="button" class="btn btn-{{($grapSales == '' ?'warning':'success')}}" onclick="window.location='/backoffice/dashboard'">This Week
                                    </button>
                                    {{-- <button type="button" class="btn btn-{{($filterTopSales5 == 'M' ?'warning':'success')}}"
                                    onclick="window.location='?channel=M'">This Year
                                    </button> --}}
                                    <button type="button" class="btn btn-{{($grapSales == 'Y' ?'warning':'success')}}" onclick="window.location='?grapSales=Y'">This Year
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="d-flex">
                            <div class="col-4">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg">

                                        ฿
                                        @php
                                        if($order_detail->total_sales != null){
                                        echo number_format($order_detail->total_sales,0,"",",");
                                        }else{
                                        echo 0;
                                        }
                                        @endphp
                                    </span>
                                    <span>Amount</span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg">

                                        ฿
                                        @php
                                        if($order_detail->total_sales != null){
                                        echo number_format($sum_discount,0,"",",");
                                        }else{
                                        echo 0;
                                        }
                                        @endphp
                                    </span>
                                    <span>Discount</span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg">

                                        ฿
                                        @php
                                        if($order_detail->total_sales != null){
                                        echo number_format($order_detail->total_sales - $sum_discount,0,"",",");
                                        }else{
                                        echo 0;
                                        }
                                        @endphp
                                    </span>
                                    <span>Total</span>
                                </p>
                            </div>
                        </div>
                        <!-- /.d-flex -->

                        <div class="position-relative mb-4">
                            <div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            {{-- width="604" --}}
                            <canvas id="sales-chart" height="200" class="chartjs-render-monitor" style="display: block; width: 100%;over-flow:x; height: 200px;"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fa fa-square text-primary"></i> Sales
                            </span>

                            <span>
                                <i class="fa fa-square text-gray"></i> discount
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border">
                        <div class="row">
                            <h3 class="card-title">สถานะรายการสินค้า</h3>
                            <div class="col-md-12">
                                <div class="card-tools" style="float: right">
                                    <button type="button" class="btn btn-{{($pieStatus == 'all' || $pieStatus == '' ?'warning':'success')}}" onclick="window.location='/backoffice/dashboard'">All
                                    </button>
                                    <button type="button" class="btn btn-{{($pieStatus == 'D' ? 'warning':'success')}}" onclick="window.location='?pieStatus=D'">This Day
                                    </button>
                                    <button type="button" class="btn btn-{{($pieStatus == 'W' ?'warning':'success')}}" onclick="window.location='?pieStatus=W'">This Week
                                    </button>
                                    <button type="button" class="btn btn-{{($pieStatus == 'M' ?'warning':'success')}}" onclick="window.location='?pieStatus=M'">This Month
                                    </button>
                                    <button type="button" class="btn btn-{{($pieStatus == 'Y' ?'warning':'success')}}" onclick="window.location='?pieStatus=Y'">This Year
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>

                </div>
                <!-- /.card -->
                <div class="card">
                    <div class="card-header no-border">
                        <div class="row">
                            <h3 class="card-title">Sales Voucher Top 10</h3>
                            <div class="col-12">
                                <div class="card-tools" style="float: right">
                                    <button type="button" class="btn btn-{{($filterTopSales5 == 'all' || $filterTopSales5 == '' ?'warning':'success')}}" onclick="window.location='/backoffice/dashboard'">All
                                    </button>
                                    <button type="button" class="btn btn-{{($filterTopSales5 == 'D' ? 'warning':'success')}}" onclick="window.location='?sales5=D'">This Day
                                    </button>
                                    <button type="button" class="btn btn-{{($filterTopSales5 == 'W' ?'warning':'success')}}" onclick="window.location='?sales5=W'">This Week
                                    </button>
                                    <button type="button" class="btn btn-{{($filterTopSales5 == 'M' ?'warning':'success')}}" onclick="window.location='?sales5=M'">This Month
                                    </button>
                                    <button type="button" class="btn btn-{{($filterTopSales5 == 'Y' ?'warning':'success')}}" onclick="window.location='?sales5=Y'">This Year
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="30%">Main Voucher</th>
                                    <th width="30%">Voucher Name</th>
                                    <th width="30%">QTY</th>
                                    <th width="30%">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($voucher_charts as $key => $voucher_chart)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $voucher_chart->name_main }}</td>
                                    <td>{{ $voucher_chart->name_voucher }}</td>
                                    <td>{{ $voucher_chart->qty }}</td>
                                    <td>{{ number_format($voucher_chart->sum_total,0,"",",") }} ฿</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card" style="height: 406px">
                    <div class="card-header no-border">

                        <div class="col-lg-12">
                            <p class="text-center">
                                <strong>Payment Channels</strong>
                            </p>
                            @php
                            $total = DB::table('tb_order')->where('status_order','=',000);
                            $order_count1 = DB::table('tb_order')->where('status_payment','=',001)->where('status_order','=',000);
                            $order_count2 = DB::table('tb_order')->where('status_payment','=',002)->where('status_order','=',000);
                            $order_count3 = DB::table('tb_order')->where('status_payment','=',003)->where('status_order','=',000);
                            if($paymentChanel == 'D'){
                            $total = $total->whereRaw("DATE(created_at) = '".date('Y-m-d')."'");
                            $order_count1 = $order_count1->whereRaw("DATE(created_at) = '".date('Y-m-d')."'");
                            $order_count2 = $order_count2->whereRaw("DATE(created_at) = '".date('Y-m-d')."'");
                            $order_count3 = $order_count3->whereRaw("DATE(created_at) = '".date('Y-m-d')."'");
                            }else if($paymentChanel == 'W'){
                            $total = $total->whereRaw("created_at BETWEEN (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY)) ");
                            $order_count1 = $order_count1->whereRaw("created_at BETWEEN (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY)) ");
                            $order_count2 = $order_count2->whereRaw("created_at BETWEEN (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY)) ");
                            $order_count3 = $order_count3->whereRaw("created_at BETWEEN (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY)) ");
                            }else if($paymentChanel == 'M'){
                            $total = $total->whereRaw("created_at BETWEEN (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH)) ");
                            $order_count1 = $order_count1->whereRaw("created_at BETWEEN (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH)) ");
                            $order_count2 = $order_count2->whereRaw("created_at BETWEEN (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH)) ");
                            $order_count3 = $order_count3->whereRaw("created_at BETWEEN (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH)) ");
                            }else if($paymentChanel == 'Y'){
                            $total = $total->whereRaw("YEAR(created_at) = '" . date('Y') . "' ");
                            $order_count1 = $order_count1->whereRaw("YEAR(created_at) = '" . date('Y') . "' ");
                            $order_count2 = $order_count2->whereRaw("YEAR(created_at) = '" . date('Y') . "' ");
                            $order_count3 = $order_count3->whereRaw("YEAR(created_at) = '" . date('Y') . "' ");
                            }
                            $total = $total->get();
                            $order_count1 = $order_count1->get();
                            $order_count2 = $order_count2->get();
                            $order_count3 = $order_count3->get();
                            if(count($total) != null){
                            $total = count($total);
                            $per5 = ($total*100)/(int)$total;
                            }else{
                            $total = 0;
                            $per5 = 0;
                            }

                            if(count($order_count1) != null){
                            $per1 = (count($order_count1)*100)/(int)$total;
                            }else{
                            $per1 = 0;
                            }
                            if(count($order_count2) != null){
                            $per2 = (count($order_count2)*100)/(int)$total;
                            }else{
                            $per2 = 0;
                            }
                            if(count($order_count3) != null){
                            $per3 = (count($order_count3)*100)/(int)$total;
                            }else{
                            $per3 = 0;
                            }


                            @endphp

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-tools" style="float: right">
                                        <button type="button" class="btn btn-{{($paymentChanel == 'all' || $paymentChanel == '' ?'warning':'success')}}" onclick="window.location='/backoffice/dashboard'">All
                                        </button>
                                        <button type="button" class="btn btn-{{($paymentChanel == 'D' ? 'warning':'success')}}" onclick="window.location='?channel=D'">This Day
                                        </button>
                                        <button type="button" class="btn btn-{{($paymentChanel == 'W' ?'warning':'success')}}" onclick="window.location='?channel=W'">This Week
                                        </button>
                                        <button type="button" class="btn btn-{{($paymentChanel == 'M' ?'warning':'success')}}" onclick="window.location='?channel=M'">This Month
                                        </button>
                                        <button type="button" class="btn btn-{{($paymentChanel == 'Y' ?'warning':'success')}}" onclick="window.location='?channel=Y'">This Year
                                        </button>
                                    </div>
                                    <br><br>
                                </div>
                            </div>
                            <div class="progress-group">
                                Credit & Debit Card
                                <span class="float-right"><b>{{ count($order_count1) }}</b>/{{ $total }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: {{ $per1 }}%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->

                            <div class="progress-group">
                                Over The Counter
                                <span class="float-right"><b>{{ count($order_count2) }}</b>/{{ $total }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger" style="width: {{ $per2 }}%"></div>
                                </div>
                            </div>

                            <!-- /.progress-group -->
                            <div class="progress-group">
                                Bank Channels
                                <span class="float-right"><b>{{ count($order_count3) }}</b>/{{ $total }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ $per3 }}%"></div>
                                </div>
                            </div>

                            <!-- /.progress-group -->

                            <div class="progress-group">
                                <span class="progress-text">Total</span>
                                <span class="float-right"><b>{{ $total }}</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ $per5 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>


                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Latest Orders</h3>


                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>รหัสออเดอร์</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>สถานะรายการสินค้า</th>
                                        <th>สถานะการส่ง Voucher</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ str_pad($order->id,11,"0",STR_PAD_LEFT) }}</td>
                                        <td>{{ $order->name_member.' '.$order->lastname_member }}</td>
                                        <td>
                                            @php
                                            $status_order = $order->status_order;
                                            if ($status_order == "000") {
                                            $status_order2 = '<span class="badge badge-success">ชำระเงินแล้ว</span>';
                                            } else if ($status_order == "001") {
                                            $status_order2 = '<span class="badge badge-warning" style="color:white;">กำลังดำเนินการ</span>';
                                            } else if ($status_order == "002") {
                                            $status_order2 = '<span class="badge badge-orange">ชำระเงินล้มเหลว</span>';
                                            } else {
                                            $status_order2 = '<span class="badge badge-danger">ยกเลิกการชำระเงิน</span>';
                                            }
                                            echo $status_order2;
                                            @endphp

                                        </td>
                                        <td>
                                            @if($order->status_send_voucher == 'scan')
                                            <span style="color:orange">ส่งแบบ Scan แล้ว</span>
                                            @elseif($order->status_send_voucher == 'true')
                                            <span style="color:green">ส่งตัวจริงแล้ว Track no.{{$order->tracking_no}}</span>
                                            @else
                                            <span style="color:blue">-ไม่ได้ทำรายการ-</span>
                                            @endif

                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">

                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->

                <!-- /.card -->
            </div>
        </div>


        <!-- /.card -->

    </section>

    <!-- right col -->

</div>

<input type="hidden" value="{!! (@$grapTotalPrice[0] == '' ? 0 :  @$grapTotalPrice[0]) !!}" id="m1">
<input type="hidden" value="{!! (@$grapTotalPrice[1] == '' ? 0 :  @$grapTotalPrice[1]) !!}" id="m2">
<input type="hidden" value="{!! (@$grapTotalPrice[2] == '' ? 0 :  @$grapTotalPrice[2]) !!}" id="m3">
<input type="hidden" value="{!! (@$grapTotalPrice[3] == '' ? 0 :  @$grapTotalPrice[3]) !!}" id="m4">
<input type="hidden" value="{!! (@$grapTotalPrice[4] == '' ? 0 :  @$grapTotalPrice[4]) !!}" id="m5">
<input type="hidden" value="{!! (@$grapTotalPrice[5] == '' ? 0 :  @$grapTotalPrice[5]) !!}" id="m6">
<input type="hidden" value="{!! (@$grapTotalPrice[6] == '' ? 0 :  @$grapTotalPrice[6]) !!}" id="m7">
<input type="hidden" value="{!! (@$grapTotalPrice[7] == '' ? 0 :  @$grapTotalPrice[7]) !!}" id="m8">
<input type="hidden" value="{!! (@$grapTotalPrice[8] == '' ? 0 :  @$grapTotalPrice[8]) !!}" id="m9">
<input type="hidden" value="{!! (@$grapTotalPrice[9] == '' ? 0 :  @$grapTotalPrice[9]) !!}" id="m10">
<input type="hidden" value="{!! (@$grapTotalPrice[10] == '' ? 0 :  @$grapTotalPrice[10]) !!}" id="m11">
<input type="hidden" value="{!! (@$grapTotalPrice[11] == '' ? 0 :  @$grapTotalPrice[11]) !!}" id="m12">


<input type="hidden" value="{!! (@$grapTotalPriceDiscount[0] == '' ? 0 : @$grapTotalPriceDiscount[0]) !!}" id="md1">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[1] == '' ? 0 :  @$grapTotalPriceDiscount[1]) !!}" id="md2">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[2] == '' ? 0 :  @$grapTotalPriceDiscount[2]) !!}" id="md3">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[3] == '' ? 0 :  @$grapTotalPriceDiscount[3]) !!}" id="md4">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[4] == '' ? 0 :  @$grapTotalPriceDiscount[4]) !!}" id="md5">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[5] == '' ? 0 :  @$grapTotalPriceDiscount[5]) !!}" id="md6">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[6] == '' ? 0 :  @$grapTotalPriceDiscount[6]) !!}" id="md7">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[7] == '' ? 0 :  @$grapTotalPriceDiscount[7]) !!}" id="md8">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[8] == '' ? 0 :  @$grapTotalPriceDiscount[8]) !!}" id="md9">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[9] == '' ? 0 :  @$grapTotalPriceDiscount[9]) !!}" id="md10">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[10] == '' ? 0 :  @$grapTotalPriceDiscount[10]) !!}" id="md11">
<input type="hidden" value="{!! (@$grapTotalPriceDiscount[11] == '' ? 0 :  @$grapTotalPriceDiscount[11]) !!}" id="md12">

<input type="hidden" value="{{$grapSales}}" id="grapSales">
<input type="hidden" value="{{$pieStatusArray['pieStatusSuccess']}}" id="statusSuccess">
<input type="hidden" value="{{$pieStatusArray['pieStatusPending']}}" id="statusPending">
<input type="hidden" value="{{$pieStatusArray['pieStatusExpired']}}" id="statusExpired">
<input type="hidden" value="{{$pieStatusArray['pieStatusCancel']}}" id="statusCancel">
<input type="hidden" value="{{$pieStatusArray['pieStatusAll']}}" id="statusAll"> 
@endsection

@section('script')

<script>
    window.onload = function() {
        var successs_ = $('#statusSuccess').val();
        var pending_ = $('#statusPending').val();
        var expired_ = $('#statusExpired').val();
        var cancel_ = $('#statusCancel').val();
        // var data_ = ['success':successs_,'pending':pending_,'expired_':expired_,'cancel':cancel_];
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "จำนวนสถานะการชำระเงินทั้งหมด " + $('#statusAll').val() + ' รายการ',
                fontSize: 18,
                horizontalAlign: "left"
            },
            data: [{
                type: "pie",
                startAngle: 60,
                //innerRadius: 60,
                indexLabelFontSize: 17,
                indexLabel: "{label} - #percent%",
                toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                dataPoints: [{
                        y: parseFloat(successs_),
                        label: "สำเร็จ",
                        color: "green"
                    },
                    {
                        y: parseFloat(pending_),
                        label: "กำลังดำเนินการ",
                        color: "yellow"
                    },
                    {
                        y: parseFloat(expired_),
                        label: "ยกเลิกคำสั่งซื้อ",
                        color: "orange"
                    },
                    {
                        y: parseFloat(cancel_),
                        label: "ชำระเงินล้มเหลว",
                        color: "red"
                    },

                ]
            }]
        });
        chart.render();

    }

    $('#datepickerDay').change(function(){
        window.location = "?grapSales="+$(this).val()
    });

    $(function() {
        $('#datepickerDay').datepicker({
            format:'dd-mm-yyyy',
        },'setDate', new Date());
        
        var m1 = $('#m1').val();
        var m2 = $('#m2').val();
        var m3 = $('#m3').val();
        var m4 = $('#m4').val();
        var m5 = $('#m5').val();
        var m6 = $('#m6').val();
        var m7 = $('#m7').val();
        var m8 = $('#m8').val();
        var m9 = $('#m9').val();
        var m10 = $('#m10').val();
        var m11 = $('#m11').val();
        var m12 = $('#m12').val();

        var md1 = $('#md1').val();
        var md2 = $('#md2').val();
        var md3 = $('#md3').val();
        var md4 = $('#md4').val();
        var md5 = $('#md5').val();
        var md6 = $('#md6').val();
        var md7 = $('#md7').val();
        var md8 = $('#md8').val();
        var md9 = $('#md9').val();
        var md10 = $('#md10').val();
        var md11 = $('#md11').val();
        var md12 = $('#md12').val();

        var grapSales = $('#grapSales').val();
        var d = new Date();

        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
            "October", "November", "December"
        ];
        var weeks = ["Monday", "Tueday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        var day = [
            "00:00-01:59 น.", "02:00-03:59 น.", "04:00-05:59 น.", "06:00 - 07:59 น.", "08:00 - 09:59 น.", "10:00 - 11:59 น.",
          "12:00 - 13:59 น.","14:00 - 15:59 น.","16:00 - 17:59 น.","18:00 - 19:59 น.","20:00 - 21:59 น.", "22:00 - 23:59 น."
         ];
        var labelDetail = [];
        var j = 0

        // months[d.getMonth() - 4]
        // console.log(d.getMonth());
        if (grapSales == 'Y') {
            sales = [m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12];
            discount = [md1, md2, md3, md4, md5, md6, md7, md8, md9, md10, md11, md12];
            for (i = 0; i <= 11; i++) {
                labelDetail[j] = months[i]
                j++;
            }
        } else if(grapSales.length > 3){
            sales = [m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12];
            discount = [md1, md2, md3, md4, md5, md6, md7, md8, md9, md10, md11, md12];
            for (i = 0; i <= 11; i++) {
                labelDetail[j] = day[i]
                j++;
            }
        }else if(grapSales == 'D'){
              sales = [m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12];
            discount = [md1, md2, md3, md4, md5, md6, md7, md8, md9, md10, md11, md12];
            for (i = 0; i <= 11; i++) {
                labelDetail[j] = day[i]
                j++;
            }
        }
        else{
            sales = [m1, m2, m3, m4, m5, m6, m7];
            discount = [md1, md2, md3, md4, md5, md6, md7];
            for (i = 0; i <= 6; i++) {
                labelDetail[j] = weeks[i]
                j++;
            }

        }

        // console.log(sales);

        'use strict'

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }

        var mode = 'index'
        var intersect = true

        var $salesChart = $('#sales-chart')
        var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
                labels: labelDetail,
                datasets: [{
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        data: sales
                    },
                    {
                        backgroundColor: '#ced4da',
                        borderColor: '#ced4da',
                        data: discount
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return '฿' + value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })

        var $visitorsChart = $('#visitors-chart')
        var visitorsChart = new Chart($visitorsChart, {
            data: {
                labels: ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
                datasets: [{
                        type: 'line',
                        data: [100, 120, 170, 167, 180, 177, 160],
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                        // pointHoverBackgroundColor: '#007bff',
                        // pointHoverBorderColor    : '#007bff'
                    },
                    {
                        type: 'line',
                        data: [60, 80, 70, 67, 80, 77, 100],
                        backgroundColor: 'tansparent',
                        borderColor: '#ced4da',
                        pointBorderColor: '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill: false
                        // pointHoverBackgroundColor: '#ced4da',
                        // pointHoverBorderColor    : '#ced4da'
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            suggestedMax: 200
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
    })
</script>
{{-- <script src="{{URL::asset('backoffice/dist/js/pages/dashboard3.js')}}"></script>--}}
<script src="{{URL::asset('backoffice/plugins/chart.js/Chart.min.js')}}"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


@endsection