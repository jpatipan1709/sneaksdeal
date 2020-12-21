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
            color:#ffffff;
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
            color:#ffffff;


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
                <a href="'.insertHttp($rowBanner->link_banner).'" target="_bank"><img class="d-block w-100" src="'.url('storage/banner/'.$rowBanner->name_banner).'"></a>
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


        <div class="row">
            @foreach($filter AS $key => $value)
                <div class="col top2rem d-none d-sm-block">
                    <a class="btn btn-md btn-block btnfliter {!! ($id == ($key+1) ?'active':($id == '' && $key == 0 ?'active':'')) !!} "
                       data-id="{!! $key+1 !!}"
                       onclick="filterBy({!! $key+1 !!})" id="btnfliter{!! $key+1 !!}" href="javascript:"
                       role="button">{!! ($key == 1 ?'<i style="color:red" class="fab fa-hotjar"></i>':'') !!}
                        {{ $value->name_filter }}</a>
                </div>
                @if($key == 1)
                    @foreach($type_vouchers AS $value_)
                        <div class="col top2rem d-none d-sm-block">
                            <a class="btn btn-md btn-block btnfliter {!! ($id == $value_->code_type ?'active':'') !!}"
                               data-id="{!! $value_->code_type!!}"
                               onclick="filterBy('{!! $value_->code_type !!}')" id="btnfliter{!! $value_->code_type !!}"
                               href="javascript:void(0)"
                               role="button">
                                {{ $value_->name_type }}</a>
                        </div>
                    @endforeach
                @endif

            @endforeach
        </div>
        <div class="row top2rem d-block d-sm-none">

            <div class="col-12">
                <div class="dropdown ">
                    <button class="btn btn-default form-control dropdown-toggle btn-show-text" type="button"
                            data-toggle="dropdown">
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu filterMobile form-control">
                        @foreach($filter AS $key => $value)
                            <li class="{!! ($id == ($key+1) ?'active':($id == '' && $key == 0 ?'active':'')) !!}" data-id="{{($key+1)}}">
                                <a href="?category={{$key+1}}">{!! ($key == 1 ?'<i style="color:red" class="fab fa-hotjar"></i>':'') !!} {{$value->name_filter}}</a>
                            </li>
                            @if($key == 1)
                                @foreach($type_vouchers AS $value_)
                                    <li class="{!! ($id == $value_->code_type ?'active':'') !!}" data-id="{{($key+1)}}">
                                        <a href="?category={{$value_->code_type}}">{{$value_->name_type}}</a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
                {{--<select id="inputState"  onchange="filterBy2(this.value)" class="form-control"--}}
                {{--style="font-family:  kanit;">--}}
                {{--@foreach($filter AS $key => $value)--}}
                {{--<option--}}
                {{--value="{!! $key+1 !!}" {!! ($id == ($key+1) ?'selected':($id == '' && $key == 0 ?'selected':'')) !!}>--}}
                {{--{{$value->name_filter}}--}}

                {{--</option>--}}
                {{--@if($key == 1)--}}
                {{--@foreach($type_vouchers AS $value_)--}}
                {{--<option value="{!! $value_->code_type !!}" {!! ($id == $value_->code_type ?'selected':'') !!}>{{$value_->name_type}}</option>--}}

                {{--@endforeach--}}
                {{--@endif--}}
                {{--@endforeach--}}

                {{--</select>--}}
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
                        <div class="saletext">{{$percen}}% OFF TODAY</div>
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
                                <div class="txtCountdownExpired">
                                    <span>หมดแล้ว!!</span>
                                </div>
                            @endif
                        </div>

                        @if($rowVoucher->type_voucher == 'in')
                            <div class="type-text"><img src="{{url('img/LOGO_SNEAKDEAL2.png')}}"></div>
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
                            {!! addCol(count($file)) !!}

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
                                <div class="alertExpired">
                                    <span>หมดแล้ว!!</span>
                                </div>
                            @endif
                            <br>
                            <div class="detialtraveltext top1rem">{!! mb_substr($rowVoucher->detail_main,0,280,'UTF-8').'...' !!}</div>
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
                               href="{{ url("/voucherdetail",$rowVoucher->voucher_id) }}" role="button">
                                ดูรายละเอียดเพิ่มเติม</a>
                            <div class="detialtraveltext" style=" position:  absolute;bottom: 20px;color:red;">
                                สต๊อค  {{$rowVoucher->qty_voucher}} ใบ <br>
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
        function runFilterMobile() {
            var txt = $('.filterMobile li.active a').html();
            $('.btn-show-text').html(txt);
        }
        function filterBy(no) {
            window.location = '?category=' + no;
            // $('.btnfliter').removeClass('active');
            // $('#btnfliter' + no).addClass('active');
            {{--$.ajax({--}}
            {{--url: '{{ url('voucherbrowsing') }}/' + no + '',--}}
            {{--data: {num: no},--}}
            {{--type: 'GET',--}}
            {{--beforeSend: function () {--}}
            {{--$('#div-loading-page').fadeIn();--}}
            {{--},--}}
            {{--success: function (data) {--}}
            {{--$('#filterByS').html(data);--}}
            {{--readPagination();--}}
            {{--$('#div-loading-page').fadeOut();--}}

            {{--}--}}
            {{--});--}}
        }

        function filterBy2(no) {
            window.location = '?category=' + no;

            {{--$.ajax({--}}
            {{--url: '{{ url('voucherbrowsing') }}/' + no + '',--}}
            {{--data: {num: no},--}}
            {{--type: 'GET',--}}
            {{--beforeSend: function () {--}}
            {{--$('#div-loading-page').fadeIn();--}}
            {{--},--}}
            {{--success: function (data) {--}}
            {{--$('#filterByS').html(data);--}}
            {{--readPagination();--}}
            {{--$('#div-loading-page').fadeOut();--}}

            {{--}--}}
            {{--});--}}
        }

        $(document).ready(function () {
            $("#voucher").addClass("active");
            readPagination();
            runFilterMobile();
        });

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