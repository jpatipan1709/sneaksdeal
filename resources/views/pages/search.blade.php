@extends('layouts.components')
@section('contentFront')

    <script>
        function formatting(target) {
            return target < 10 ? '0' + target : target;
        }

        function countdownTime(date, id, stat) {
            var countDownDate = new Date("" + date + "").getTime();

            // Update the count down every 1 second
            var x = setInterval(function () {
                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = parseFloat(countDownDate) - parseFloat(now);

                // Time calculations for days, hours, minutes and seconds
                var dayss = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                var Day = formatting(dayss);
                var textDay = " days ";
                var Time = formatting(hours) + ":" + formatting(minutes) + ":" + formatting(seconds);
                // Output the result in an element with id="demo"
                $('#timeCountdownD' + id).text(Day);
                $('#timeCountdownT' + id).text(textDay);
                $('#timeCountdownH' + id).text(Time);
                if (stat == 'post') {
                    $('#timeCountdownWaiting' + id).show();
                    $('.daylifetext' + id).attr('style', '')
                } else {
                    $('#timeCountdownWaiting' + id).hide();
                    $('.daylifetext' + id).attr('style', '')

                }
                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    $('#timeCountdownD' + id + '').html("หมดเวลา");
                    $('#timeCountdownT' + id + '').html("");
                    $('#timeCountdownH' + id + '').html("");


                }
            }, 1000);
        }
        function countdownTimeMobile(date, id, stat) {
            var countDownDate = new Date("" + date + "").getTime();

            // Update the count down every 1 second
            var x = setInterval(function () {
                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = parseFloat(countDownDate) - parseFloat(now);

                // Time calculations for days, hours, minutes and seconds
                var dayss = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                var Day = formatting(dayss);
                var textDay = " days ";
                var Time = formatting(hours) + ":" + formatting(minutes) + ":" + formatting(seconds);
                // Output the result in an element with id="demo"
                if (stat == 'post') {
                    $('#MTimeCountdownWaiting' + id).show();
                    $('.daylifetext' + id).attr('style', '')
                } else {
                    $('#MTimeCountdownWaiting' + id).hide();
                    $('.daylifetext' + id).attr('style', '')

                }

                $('#MTimeCountdownD' + id).text(Day);
                $('#MTimeCountdownT' + id).text(textDay);
                $('#MTimeCountdownH' + id).text(Time);

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    $('#MTimeCountdownD' + id + '').html("หมดเวลา");
                    $('#MTimeCountdownT' + id + '').html("");
                    $('#MTimeCountdownH' + id + '').html("");


                }
            }, 1000);
        }
    </script>

    @php

        $noVWhere = [];
    @endphp
    @php
        function addCol($count){
            if($count == 1){
                echo  '<div class="col" style="padding: 0;"></div>';
                echo  '<div class="col" style="padding: 0;"></div>';
                echo  '<div class="col" style="padding: 0;"></div>';
                echo  '<div class="col" style="padding-left: 0;"></div>';
            }else if($count == 2){
                echo  '<div class="col" style="padding: 0;"></div>';
                echo  '<div class="col" style="padding: 0;"></div>';
                echo  '<div class="col" style="padding-left: 0;"></div>';
            }
            else if($count == 3 ){
                echo  '<div class="col" style="padding: 0;"></div>';
                echo  '<div class="col" style="padding-left: 0;"></div>';
            }
            else if($count == 4){
                echo  '<div class="col" style="padding-left: 0;"></div>';
            }else {
                echo '';
            }

        }

    @endphp
    <style>
        .detail-main{
            display:block;
            max-height: 420px;
            /*overflow-y:auto;*/
        }
    </style>
    <div class="container">

        <div class="row">

            <div class="col-12">
                <input type="hidden" value="{{$search}}" id="valSearch">
                <div class="headtext top2rem">ผลการค้นหา '<span>{{$search}}</span>'</div>
            </div>
        </div>
        <div class="row">
             <div class="col top2rem d-none d-sm-block">
             <a class="btn btn-md btn-block btnfliter {{($type == '' || $type == '0' ? 'active': '')}}"
                       data-id="0"
                       onclick="filterBy('0')" id="btnfliter0"
                       href="javascript:void(0)"
                       role="button">
             ทั้งหมด</a>
                </div>
            @foreach($typeVoucher AS $key => $value)
              
                <div class="col top2rem d-none d-sm-block">
                    <a class="btn btn-md btn-block btnfliter {!! ($type == $value->code_type ?'active':'') !!} "
                       data-id="{!! $value->code_type !!}"
                       onclick="filterBy('{!! $value->code_type !!}')" id="btnfliter{!! $value->code_type !!}"
                       href="javascript:void(0)"
                       role="button">
                        {{ $value->name_type }}</a>
                </div>

            @endforeach
        </div>
        <div class="row top2rem d-block d-sm-none">

            <div class="col-12">
                <select id="inputState" onchange="filterBy2(this.value)" class="form-control"
                        style="font-family:  kanit;">
                        <option {{$type == 0 || $type == '' ?'selected':''}} value="0">ทั้งหมด</option>

                    @foreach($typeVoucher AS $key => $value)
                        <option {{$type== $value->code_type ?'selected':''}} value="{!! $value->code_type !!}">{{$value->name_type}}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 top2rem">

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">ดีล</div>
                    </li>
                    @if(@count($voucher) > 0)
                        @foreach($voucher AS $key => $rowVoucher)
                            @php
                                $dateExpired = strtotime($rowVoucher->date_close);
                     $thisTime = time();
                     $totalExpired = time() - $dateExpired;
                     $dayFive = 86400 * 5;

                         $order = DB::table('tb_order')
                                          ->leftjoin('tb_order_detail','tb_order.id','=','tb_order_detail.order_id')
                                          ->where('tb_order_detail.voucher_id', '=', $rowVoucher->voucher_id)
                                          ->where('tb_order.status_order', '=', '000')
                                          ->get();
                         $Datetest =  date_format(date_create($rowVoucher->date_close), "F j, Y H:i:s");

                         $file = DB::table('system_file')
                                      ->where('relationId', '=', $rowVoucher->id_main)
                                      ->where('relationTable', '=', 'main')
                                      ->offset(1)
                                      ->limit(100)
                                      ->orderBy('sort_img','asc')

                                      ->get();

                         $file2 = DB::table('system_file')
                                      ->where('relationId', '=', $rowVoucher->id_main)
                                      ->where('relationTable', '=', 'main')
                                      ->orderBy('sort_img','asc')
                                      ->first();
                            if($rowVoucher->price_agent != "" && $rowVoucher->sale != ""){
                                $percen = salePercen($rowVoucher->price_agent,$rowVoucher->sale);
                            }else{
                                $percen = 0;
                            }

                             if($rowVoucher->status_countdown == 'post'){
                            $dateEndCountdown = date_format(date_create($rowVoucher->date_open), "F j, Y H:i:s");
                            }else{
                            $dateEndCountdown =  date_format(date_create($rowVoucher->date_close), "F j, Y H:i:s");
                            }
                            @endphp
                            {{-- @if(($dayFive >= $totalExpired && $rowVoucher->expired != 'y') || ($dayFive <= $totalExpired && $rowVoucher->expired == 'y') || ($dayFive >= $totalExpired)  ) --}}
                                <li class="list-group-item d-flex  lh-condensed">
                                    <a href="">
                                        <div class="row box">
                                            <div class="col-lg-5 col-md-6 col-12" style="padding:  0px;">
                                                <div class="saletext" style="color:black;">{{$percen}}% OFF TODAY</div>
                                                <div class="d-block d-md-none ">
                                                    <div class="txtCountdown daylifetext{{ $rowVoucher->voucher_id }}">
                                                        <span id="MTimeCountdownWaiting{{ $rowVoucher->voucher_id }}">เปิดขายในอีก <br></span>
                                                        <span id="MTimeCountdownD{{ $rowVoucher->voucher_id }}"></span>
                                                        <span id="MTimeCountdownT{{ $rowVoucher->voucher_id }}"></span>
                                                        <span id="MTimeCountdownH{{ $rowVoucher->voucher_id }}"></span>

                                                    </div>
                                                </div>
                                                <script>countdownTimeMobile('{{ $dateEndCountdown }}', '{{ $rowVoucher->voucher_id }}', '{{$rowVoucher->status_countdown}}');</script>

                                            @if($rowVoucher->type_voucher == 'in')
                                                    <div class="type-text"><img
                                                                src="{{url('img/LOGO_SNEAKDEAL2.png')}}"></div>
                                                @endif
                                                @if(@$file2->name != null)
                                                    <a data-fancybox="gallery{{$key}}"
                                                       href="{{url('storage/main/'.$file2->name)}}">
                                                        <div class="controlimgboxvoucherbig">
                                                            <img class="imgbox03"
                                                                 src="{{ url('storage/main/'.$file2->name) }}">
                                                        </div>
                                                    </a>
                                                @else
                                                    <a data-fancybox="gallery{{$key}}"
                                                       href="{{ url('img/voucherbrowsing/img23.png') }}">
                                                        <img class="imgbox"
                                                             src="{{ url('img/voucherbrowsing/img23.png') }}">
                                                    </a>
                                                @endif
                                                <div class="row">
                                                    @php
                                                        $noV = 1;
                                                    @endphp
                                                    @foreach($file AS $key_row => $rowFile)

                                                        <div class="col"
                                                             style="{{ ($noV == 1 ?'padding-right: 0;': (5 === $noV ?'padding-left: 0;':'padding: 0;')) }} {{ $noV > 5 ? 'display:none' : '' }}">
                                                            <a data-fancybox="gallery{{  $key }}"
                                                               href="{{url('storage/main/'.$rowFile->name)}}">
                                                                <div class="controlimgboxvoucher">
                                                                    <img class="imgbox"
                                                                         src="{{url('storage/main/'.$rowFile->name)}}">
                                                                </div>
                                                            </a>
                                                        </div>
                                                        @php $noV++; @endphp
                                                    @endforeach
                                                    {!! addCol(count($file)) !!}

                                                </div>

                                            </div>
                                            <script>countdownTime('{{ $dateEndCountdown }}', '{{ $rowVoucher->voucher_id }}', '{{$rowVoucher->status_countdown}}');</script>
                                            <div class="col-lg-7 col-md-6 col-12">
                                                <a href="{{ url('/voucherdetail',$rowVoucher->voucher_id) }}">
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-7 col-12">
                                                            <div class="headtext top1rem">
                                                                <div class="top-voucher">
                                                                    {{ $rowVoucher->name_main }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="daylifetext">
                                                        <span id="timeCountdownWaiting{{ $rowVoucher->voucher_id }}">เปิดขายในอีก <br></span>
                                                        <span id="timeCountdownD{{ $rowVoucher->voucher_id }}"></span>
                                                        <span id="timeCountdownT{{ $rowVoucher->voucher_id }}"></span>
                                                        <span id="timeCountdownH{{ $rowVoucher->voucher_id }}"></span>

                                                    </div>
                                                    <br>

                                                        <div class="detialtraveltext top1rem detail-main">{!! $rowVoucher->detail_main !!}
                                                        </div>
                                                    <div class="travelnormaltext top1rem">{{ $rowVoucher->address_main }}</div>

                                                    <div class="row">
                                                        <div class="offset-md-6  col-md-6">
                                                            <div class="pricetext text-right ">เริ่มต้นที่</div>
                                                            <div class="bahttext text-right top-0rem"
                                                                 style="text-decoration: line-through;color: #707070;">
                                                                ฿ {{number_format($rowVoucher->price_agent)}}
                                                            </div>
                                                            <div class="bahttext text-right top-0rem"
                                                                 style="color: red;font-weight: 600;">
                                                                ฿ {{number_format($rowVoucher->price_sale)}}
                                                            </div>
                                                            <div class="detialtraveltext text-right">{{$rowVoucher->qty_night}}</div>
                                                        </div>
                                                    </div>

                                                    <a class="btn btn-md btnsneakout"
                                                       href="{{ url("/voucherdetail",$rowVoucher->voucher_id) }}"
                                                       role="button">
                                                        ดูรายละเอียดเพิ่มเติม</a>
                                                    <div class="detialtraveltext"
                                                         style=" position:  absolute;bottom: 20px;color:red;">

                                                        @if($rowVoucher->show_sale =='y')
                                                            ขายไปแล้ว
                                                            @php

                                                                $count_voucher = App\Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
                                                                        ->leftjoin('tb_order_detail','order_vouchers.order_detail_id','=','tb_order_detail.odt_id')
                                                                        ->where('main_id','=',$rowVoucher->relation_mainid)
                                                                        // ->where('status_order', '=', '000')
                                                                        ->where(function ($query) {
                                                                            $query->where('status_order', '=', '000')
                                                                                ->orWhere('status_order', '=', '001');
                                                                        })
                                                                        // ->where('status_order','=','000')
                                                                        // ->orWhere('status_order','=','002')
                                                                        ->count();
                                                            @endphp
                                                            @if ($rowVoucher->stat_sale == 'y')
                                                                {{($count_voucher == null ? '0' : $count_voucher ).' ดีล'}}
                                                            @else
                                                                {{($rowVoucher->detail_stat_sale == null ? '0' : $rowVoucher->detail_stat_sale ).' ดีล'}}
                                                            @endif
                                                            ดีล
                                                        @endif
                                                    </div>
                                                    <div class="bottravel"></div>
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            {{-- @endif --}}

                        @endforeach
                    @endif
                    {{--</ul>--}}
                    <div class="row top2rem">
                        <div class="col-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end pagination-voucher"
                                    style="margin: 0;float: right">
                                    {!! $voucher->links() !!}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </ul>
            </div>

        </div>

    </div>

@endsection


@section('scriptFront')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-147734154-1"></script>
    @if($activeStatus == 'true')
        <script>
            $(document).ready(function () {
                $("#province").addClass("active");
            });
        </script>
    @endif
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-147734154-1');
    </script>
    <script>
        $(document).ready(function () {
            $("#account").addClass("active");

            var valSearch = $('#valSearch').val();
            if (valSearch != '') {
                $('#searchTop').val(valSearch);
            }
        });

        function filterBy(no) {
            // $('.btnfliter').removeClass('active');
            // $('#btnfliter' + no).addClass('active');
            window.location = '?type=' + no;
            {{--$.ajax({--}}
            {{--    url: '{{ url('search') }}/' + no + '',--}}
            {{--    data: {num: no},--}}
            {{--    type: 'GET',--}}
            {{--    beforeSend: function () {--}}
            {{--        $('#div-loading-page').fadeIn();--}}
            {{--    },--}}
            {{--    success: function (data) {--}}
            {{--        $('#filterByS').html(data);--}}
            {{--        readPagination();--}}
            {{--        $('#div-loading-page').fadeOut();--}}

            {{--    }--}}
            {{--});--}}
        }

        function filterBy2(no) {
            window.location = '?type=' + no;

            {{--$.ajax({--}}
            {{--    url: '{{ url('voucherbrowsing') }}/' + no + '',--}}
            {{--    data: {num: no},--}}
            {{--    type: 'GET',--}}
            {{--    beforeSend: function () {--}}
            {{--        $('#div-loading-page').fadeIn();--}}
            {{--    },--}}
            {{--    success: function (data) {--}}
            {{--        $('#filterByS').html(data);--}}
            {{--        readPagination();--}}
            {{--        $('#div-loading-page').fadeOut();--}}
            {{--    }--}}
            {{--});--}}
        }
    </script>
@endsection