@extends('layouts.components')
@php
    function insertHttp($text)
    {
        if (preg_match('/http/', $text)) {
            return $text;
        } else {
            return 'http://'.$text;
        }
    }
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
    /* .alertExpired {
        position: absolute;
        top: 74px;
        right: 0px;
        background-color: #ff0000cc;
        padding: 7px 10px 5px 20px;
        font-family: 'Kanit', sans-serif;
        font-weight: 600;
        font-size: 18px;
        color: #ffffff;
        min-width: 140px;
        text-align: center
    } */

    .alertExpired {
        width: 150px;
    }


    .alertExpired {
        position: absolute;
        top: 20px;
        right: 0px;
        background-color: #ff0000cc;
        padding: 7px 20px 5px 20px;
        font-family: 'Kanit', sans-serif;
        font-weight: 600;
        font-size: 20px;
        color: #ffffff;
        min-width: 200px;
    }

    .btn-show-detail {
        display: none !important;
    }

    .detail-box {
        font-family: 'Kanit', sans-serif;
        font-size: 16px;
        color: #454545;
    }

    .detail-box-show {
        font-family: 'Kanit', sans-serif;
        font-size: 16px;
        color: #454545;
    }

    .type-text img {
        width: 100px !important;
        height: 50px !important;
    }

    @media (max-width: 1199.98px) and (min-width: 250px) {
        .btn-show-detail {
            display: block !important;
        }

        .detail-box {
            font-family: 'Kanit', sans-serif;
            font-size: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    @media (max-width: 767px) and (min-width: 100px) {
        .txtCountdownExpired {
            position: absolute;
            top: 60px;
            left: 0px;
            z-index: 25;
            background-color: #ff0000cc;
            padding: 7px 15px 5px 15px;
            font-family: roboto;
            font-weight: 600;
            font-size: 15px;
            color: #ffffff;
            width: 150px;
        }

        .alertExpired {
            display: none;
        }


    }


    @media (max-width: 424px) and (min-width: 414px) {
        .alertExpired {
            position: absolute;
            top: -280px;
            left: 0px;
            background-color: #ff0000cc;
            padding: 7px 15px 5px 15px;
            font-family: roboto;
            font-weight: 600;
            font-size: 15px;
            width: 170px;
            color: #ffffff;


        }
    }

</style>
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
                if (stat == 'post') {
                    $('#timeCountdownWaiting' + id).show();
                    $('.daylifetext' + id).attr('style', '')
                } else {
                    $('#timeCountdownWaiting' + id).hide();
                    $('.daylifetext' + id).attr('style', '')

                }

                $('#timeCountdownD' + id).text(Day);
                $('#timeCountdownT' + id).text(textDay);
                $('#timeCountdownH' + id).text(Time);

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

    <style>
        .filterMobile li {
            border-bottom: 0.5px solid #c0c4cca8;

            padding: 5px;
        }

        .filterMobile .active {
            background-color: #cae4ff;
        }

        .detail-single {
            font-family: 'Kanit', sans-serif;
            font-size: 17px;
            display: -webkit-box;
            /*-webkit-line-clamp: 4;*/
            /*-webkit-box-orient: vertical;*/
            /* overflow: hidden; */
            text-overflow: ellipsis;
            color: #454545;
        }
    </style>
    @php


        $countBanner = count($banner);

    $noVWhere = [];
    @endphp

    {{-- {{ dd(Auth::check()) }} --}}
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @php
                for ($j = 0 ;$j<$countBanner;$j++){
                 echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$j.'" class="'. ($j==0?'active':'').'"></li>';
                }
            @endphp
            {{--<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>--}}
            {{--<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>--}}
        </ol>
        <div class="carousel-inner">
            @php $i = 1;
            foreach($banner AS $rowBanner){
            echo '<div class="carousel-item '. ($i==1?'active':'').'">
                <a href="'.insertHttp($rowBanner->link).'" target="_bank"><img class="d-block w-100" src="'.url('storage/banner_single_journey/'.$rowBanner->file_name).'"></a>
            </div>';
            $i++;}
            @endphp


            {{--<div class="carousel-item">--}}
            {{--<img class="d-block w-100" src="img/voucherbrowsing/img13.png">--}}
            {{--</div>--}}
            {{--<div class="carousel-item">--}}
            {{--<img class="d-block w-100" src="img/voucherbrowsing/img13.png">--}}
            {{--</div>--}}
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container">
        <div class="top1rem"></div>
        <form autocomplete="off" class="form-inline my-2 my-lg-0 hiddenipad" onsubmit="return ClickSearch()"
              method="get">

            <div class="row box" style="padding: 20px;border-radius: 15px">
                <div class=" col-md-12">
                    <div class="input-group md-form form-sm form-2 pl-0" style="margin-right:13px ">
                        <input autocomplete="off" style="border-right:none"
                               class="form-control my-0 py-1 amber-border ui-autocomplete-input" id="inputSingleJourney"
                               name="inputSingleJourney" type="text" value="{{(@$search == ''?'':$search)}}"
                               placeholder="ค้นหาชื่อโครงการ" aria-label="Search">
                        <div class="input-group-append" style="cursor: pointer">
                            <a style="border-left:none;background-color:#ffffff" onclick="ClickSearch()"
                               class="input-group-text amber lighten-3" id="basic-text1"><i
                                        class="fas fa-search text-grey" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="top1rem"></div>
        <div class="row box">
            @php
                $dbSingleJourney =  \DB::table('tb_single_journey')->first();
            @endphp
            <div class="col-12 col-md-6" style="padding:  0px;">
                <img src="{{url('storage/single_journey',$dbSingleJourney->file_name)}}" class="img-fluid">
            </div>
            <div class="col-12 col-md-6">
                <div class="headtext top1rem">“เส้นทางคนโสด Single Journey” #อย่าล้อเล่นกับความเหงา</div>
                <div id="detail-single-journey" class="detail-box top1rem" style="text-indent: 1.5em;">
                    {!! nl2br($dbSingleJourney->detail_name) !!}</div>
                <a class="btn btn-md btnsneakout btn-show-detail" href="javascript:void(0)"
                   onclick="showDetail($(this))" data-show="hide" role="button"
                   style=" color: white;">
                    ดูรายละเอียดเพิ่มเติม</a>
                <div class="bottravel"></div>
            </div>

        </div>

        <div class="row box top2rem">
            <div class="col-12">
                <div class="travelheadtext" style="padding-top: 5px;padding-bottom:  7px;">ดีลคนโสด <span
                            style="float: right">{{(@$search !='' ?'ค้นหา : "'.@$search.'"':'')}}</span>
                </div>
            </div>
        </div>

        <div id="filterByS">

            @foreach($voucher AS $key => $rowVoucher)
                {{--@if($rowVoucher->voucher_id !='' && ($rowVoucher->deleted_at == '' ||  $rowVoucher->deleted_at == null ) )--}}
                @php
                    $dateExpired = strtotime($rowVoucher->date_close);
                    $thisTime = time();
                    $totalExpired = time() - $dateExpired;
                    $dayFive = 86400 * 5;
                        if($rowVoucher->status_countdown == 'post'){
                        $dateEndCountdown = date_format(date_create($rowVoucher->date_open), "F j, Y H:i:s");
                        }else{
                        $dateEndCountdown =  date_format(date_create($rowVoucher->date_close), "F j, Y H:i:s");
                        }
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

                    $percen = salePercen($rowVoucher->price_agent,$rowVoucher->sale);

                @endphp
                {{--                    @if(($dayFive >= $totalExpired && $rowVoucher->expired != 'y') || ($dayFive <= $totalExpired && $rowVoucher->expired == 'y') || ($dayFive >= $totalExpired)  )--}}

                <div class="top2rem"></div>
                <div class="row box">
                    <div class="col-lg-5 col-md-6 col-12" style="padding:  0px;">
                        {{--                        @if($percen > 0)--}}
                        {{--                            <div class="saletext">{{$percen}}% OFF TODAY</div>--}}
                        {{--                            <div class="saletext">หมดแล้ว</div>--}}
                        {{--                        @endif--}}
                        <div class="d-block d-md-none ">
                            @if($rowVoucher->qty_voucher > 0)
                                <div class="txtCountdown daylifetext{{ $rowVoucher->voucher_id }}">
                                    <span id="MTimeCountdownWaiting{{ $rowVoucher->voucher_id }}">เปิดขายในอีก <br></span>
                                    <span id="MTimeCountdownD{{ $rowVoucher->voucher_id }}"></span>
                                    <span id="MTimeCountdownT{{ $rowVoucher->voucher_id }}"></span>
                                    <span id="MTimeCountdownH{{ $rowVoucher->voucher_id }}"></span>

                                </div>
                                <script>countdownTimeMobile('{{ $dateEndCountdown }}', '{{ $rowVoucher->voucher_id }}', '{{$rowVoucher->status_countdown}}');</script>
                            @else
                                @if($rowVoucher->type_voucher == 'in')
                                    <div class="txtCountdownExpired">
                                        @if($rowVoucher->voucher_id == 1167 )
                                            <span>ลุ้นรับฟรี!!</span>
                                        @else
                                            <span>หมดแล้ว!!</span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>

                        @if($rowVoucher->type_voucher == 'in')
                            <div class="type-text" style="margin-right: 10px"><img
                                        src="{{url('img/logo-single-journey2.png')}}"></div>
                        @endif
                        @if(isset($file2->name))
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
                            @foreach($file AS $rowFile)
                                <div class="col   "
                                     style="{{ ($noV == 1 ?'padding-right: 0;': (5 === $noV ?'padding-left: 0;':'padding: 0;')) }} {{ $noV > 5 ? 'display:none' : '' }}">

                                    <a data-fancybox="gallery{{$key}}"
                                       href="{{url('storage/main/'.$rowFile->name)}}">
                                        <div class="controlimgboxvoucher">
                                            <img class="imgbox" src="{{url('storage/main/'.$rowFile->name)}}">
                                        </div>
                                    </a>

                                </div>
                                @php $noV++; @endphp
                            @endforeach
                            {!! addCol(count(@$file)) !!}

                        </div>

                    </div>
                    <div class="col-lg-7 col-md-6 col-12">
                        <a href="{{ url("/voucherdetail",$rowVoucher->voucher_id) }}">
                            <div class="row">
                                <div class="col-lg-8 col-md-7 col-12">
                                    <div class="headtext top1rem">
                                        <div class="top-voucher">
                                            {{ $rowVoucher->name_main }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($rowVoucher->qty_voucher > 0)
                                <script>countdownTime('{{ $dateEndCountdown }}', '{{ $rowVoucher->voucher_id }}', '{{$rowVoucher->status_countdown}}');</script>
                                <div class="daylifetext daylifetext{{ $rowVoucher->voucher_id }}">
                                    <span id="timeCountdownWaiting{{ $rowVoucher->voucher_id }}">เปิดขายในอีก <br></span>
                                    <span id="timeCountdownD{{ $rowVoucher->voucher_id }}"></span>
                                    <span id="timeCountdownT{{ $rowVoucher->voucher_id }}"></span>
                                    <span id="timeCountdownH{{ $rowVoucher->voucher_id }}"></span>

                                </div>
                            @else
                                @if($rowVoucher->type_voucher == 'in')
                                    <div class="alertExpired">
                                        @if($rowVoucher->voucher_id == 1167 )
                                            <span>ลุ้นรับฟรี!!</span>
                                        @else
                                            <span>หมดแล้ว!!</span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            <br>
                            <div class="detialtraveltext top1rem">{!! mb_substr($rowVoucher->detail_main,0,280,'UTF-8').'...' !!}</div>
                            <div class="travelnormaltext top1rem">{{ $rowVoucher->address_main }}</div>

                            <div class="row">
                                <div class="offset-md-6  col-md-6">
                                    @if($rowVoucher->sale > 0)
                                        <div class="pricetext text-right ">เริ่มต้นที่</div>
                                        <div class="bahttext text-right top-0rem"
                                             style="text-decoration: line-through;color: #707070;">
                                            ฿ {{number_format($rowVoucher->price_agent)}}
                                        </div>
                                    @endif
                                    <div class="bahttext text-right top-0rem"
                                         style="color: red;font-weight: 600;">
                                        ฿ {{number_format($rowVoucher->price_sale)}}
                                    </div>
                                    <div class="detialtraveltext text-right">{{$rowVoucher->qty_night}}</div>
                                </div>
                            </div>

                            <a class="btn btn-md btnsneakout"
                               href="{{ url("/voucherdetail",$rowVoucher->voucher_id) }}" role="button">
                                ดูรายละเอียดเพิ่มเติม</a>
                            <div class="detialtraveltext" style=" position:  absolute;bottom: 20px;color:red;">
                                @if($rowVoucher->type_voucher == 'in')
                                    สต๊อค {{$rowVoucher->qty_voucher}} ใบ <br>
                                    @if($rowVoucher->show_sale =='y')
                                        ขายไปแล้ว
                                        @php
                                            $count_voucher = App\Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
                                                    ->leftjoin('tb_order_detail','order_vouchers.order_detail_id','=','tb_order_detail.odt_id')
                                                    ->where('main_id','=',$rowVoucher->relation_mainid)
                                                    ->where(function ($query) {
                                                        $query->where('status_order', '=', '000')
                                                            ->orWhere('status_order', '=', '001');
                                                    })

                                                    ->count();
                                        @endphp
                                        @if ($rowVoucher->stat_sale == 'y')
                                            {{($count_voucher == null ? '0' : $count_voucher ).' ดีล'}}
                                        @else
                                            {{($rowVoucher->detail_stat_sale == null ? '0' : $rowVoucher->detail_stat_sale ).' ดีล'}}
                                        @endif
                                    @endif
                                @endif


                            </div>
                            <div class="bottravel"></div>
                        </a>
                    </div>

                </div>
                {{--@endif--}}
                {{--@endif--}}
            @endforeach
            <div class="row top2rem">
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end pagination-voucher" style="margin: 0;float: right">
                            {!! $voucher->links() !!}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scriptFront')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-147734154-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-147734154-1');

    </script>
    <script>
        function ClickSearch() {
            var inputSingleJourney = $('#inputSingleJourney').val();
            if (inputSingleJourney.length > 3) {
                window.location = "/singlejourney/" + inputSingleJourney;

            }
            return false;
        }
    </script>
    <script>

        $(document).ready(function () {
            $("#pageSingleJourney").addClass("active");
            readPagination();
        });

        function showDetail(th_) {
            var val = th_.data("show");
            if (val == 'hide') {
                $('#detail-single-journey').removeClass('detail-box');
                $('#detail-single-journey').addClass('detail-box-show');
                th_.data('show', 'show');
            } else {
                $('#detail-single-journey').removeClass('detail-box-show');
                $('#detail-single-journey').addClass('detail-box');
                th_.data('show', 'hide');
            }
        }

        function readPagination() {
            $('.pagination-voucher li a').each(function () {
                var link_ = $(this).attr("href");
                var page = link_.split('page=')[1];
                var mobile_ = $('.filterMobile li.active').data("id");
                var pc_ = $('.btnfliter.active').data("id");
                if (mobile_ == 1 && pc_ == 1) {
                    Filter_ = 1;
                } else if (pc_ != 1) {
                    Filter_ = pc_;
                } else if (mobile_ != 1) {
                    Filter_ = mobile_;
                } else {
                    Filter_ = 1;
                }
                $(this).attr("href", link_ + '&category=' + Filter_);
                // $(this).attr("data-href",link_);
                // $(this).attr("onclick", "paginationPage(" + page + ")");
            });
        }

        function paginationPage(page) {
            var mobile_ = $('.filterMobile li.active').data("id");
            var pc_ = $('.btnfliter.active').data("id");
            if (mobile_ == 1 && pc_ == 1) {
                Filter_ = 1;
            } else if (pc_ != 1) {
                Filter_ = pc_;
            } else if (mobile_ != 1) {
                Filter_ = mobile_;
            } else {
                Filter_ = 1;
            }
            $.ajax({
                url: '{{ url('voucherbrowsing') }}/' + Filter_ + '',
                data: {
                    num: Filter_,
                    page: page
                },
                type: 'GET',
                beforeSend: function () {
                    $('#div-loading-page').fadeIn();
                },
                success: function (data) {
                    $('#filterByS').html(data);
                    readPagination();
                    $('#div-loading-page').fadeOut();
                }
            });
        }

    </script>
@endsection