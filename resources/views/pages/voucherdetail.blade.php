@extends('layouts.components')

@section('meta')
    <meta name="images" property="og:image" content="{{ url('storage/voucher/'.$voucher->img_show) }} "/>
    <meta name="description" property="og:description"
          content="{{ ($voucher->detail_main != '' ? strip_tags($voucher->detail_main) : $voucher->name_main) }}"/>
    <meta name="title" property="og:title" content="{{ $voucher->name_main }}"/>
@endsection
@section('title')
    {{ $voucher->name_main }}
@endsection
<style>
    .disabled {
        pointer-events: none;
        cursor: default;
    }

    iframe {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
    }

    .travelnormaltext {

        -webkit-line-clamp: 999 !important;
    }

    .top-voucher .txt-top {
        font-weight: bold;
        background-color: #fbdc07;
        border-top-right-radius: 50px 20px;

        background-clip: padding-box;
        padding: 5px
    }

    .alertExpired {
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
    }

    .dealposition {
        margin-bottom: 10px
    }
</style>
@php
    header('Content-type: text/plain');

function addCol($count){
if($count == 1){
echo '<div class="col" style="padding: 0;"></div>';
echo '<div class="col" style="padding: 0;"></div>';
echo '<div class="col" style="padding: 0;"></div>';
echo '<div class="col" style="padding-left: 0;"></div>';
}else if($count == 2){
echo '<div class="col" style="padding: 0;"></div>';
echo '<div class="col" style="padding: 0;"></div>';
echo '<div class="col" style="padding-left: 0;"></div>';
}
else if($count == 3 ){
echo '<div class="col" style="padding: 0;"></div>';
echo '<div class="col" style="padding-left: 0;"></div>';
}
else if($count == 4){
echo '<div class="col" style="padding-left: 0;"></div>';
}else {
echo '';
}

}
@endphp
@section('contentFront')
    <script>
        function formatting(target) {
            return target < 10 ? '0' + target : target;
        }

        function countdownTime(date, id, stat, idAjaxUpdate) {
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
                if (stat == 'post' && distance < 0) {
                    $.ajax({
                        url: '/check_voucher_countdown',
                        data: {id: idAjaxUpdate},
                        type: "GET",
                        success: function (res) {
                            if (res == 'update') {
                                location.reload();
                            }

                        }
                    });
                }
                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    $('#timeCountdownWaiting' + id).hide();
                    $('.daylifetext' + id).attr('style', '')
                    $('#timeCountdownD' + id + '').html("หมดเวลา");
                    $('#timeCountdownT' + id + '').html("");
                    $('#timeCountdownH' + id + '').html("");


                }
            }, 1000);
        }

        // function countdownTime(date, id) {
        //     var countDownDate = new Date("" + date + "").getTime();
        //
        //     // Update the count down every 1 second
        //     var x = setInterval(function () {
        //         // Get todays date and time
        //         var now = new Date().getTime();
        //
        //         // Find the distance between now an the count down date
        //         var distance = parseFloat(countDownDate) - parseFloat(now);
        //
        //         // Time calculations for days, hours, minutes and seconds
        //         var dayss = Math.floor(distance / (1000 * 60 * 60 * 24));
        //         var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        //         var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //         var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        //         var Day = formatting(dayss);
        //         var textDay = " days ";
        //         var Time = formatting(hours) + ":" + formatting(minutes) + ":" + formatting(seconds);
        //         // Output the result in an element with id="demo"
        //         $('#timeCountdownD' + id).text(Day);
        //         $('#timeCountdownT' + id).text(textDay);
        //         $('#timeCountdownH' + id).text(Time);
        //
        //         // If the count down is over, write some text
        //         if (distance < 0) {
        //             clearInterval(x);
        //             $('#timeCountdownD' + id + '').html("หมดเวลา");
        //             $('#timeCountdownT' + id + '').html("");
        //             $('#timeCountdownH' + id + '').html("");
        //
        //
        //         }
        //     }, 1000);
        // }
    </script>
    <div class="container-fluid xhidden">
        <div class="row">
            <div class="col-12 col-md-6 " style="padding:  0px;">

                @if($voucher->stat_show == 'y')
                    <a href="#">
                        <div class="vdo embed-responsive-16by9">
                            {!! $voucher->link_vdo !!}
                        </div>
                    </a>
                @else
                    @php
                        $file = DB::table('system_file')
                        ->where('relationId', '=', $voucher->id_main)
                        ->where('relationTable', '=', 'main')
                        ->orderBy('sort_img','asc')
                        ->first();

                    @endphp

                    <a data-fancybox="gallerys"
                       href="@if($file == null) {{ URL::asset('img/voucherdetail/img23.png') }} @else {{url('storage/main/'.$file->name)}} @endif">

                        <div class="controlimgboxvoucherdetail">
                            <img class="imgbox03"
                                 src="@if($file == null) {{ URL::asset('img/voucherdetail/img23.png') }} @else {{url('storage/main/'.$file->name)}} @endif">
                        </div>

                    </a>

                @endif
            </div>

            <div class="col-12 col-md-6">
                <div class="row">
                    @php
                        $file = DB::table('system_file')
                        ->where('relationId', '=', $voucher->id_main)
                        ->where('relationTable', '=', 'main')
                        ->offset(1)
                        ->limit(3)
                        ->orderBy('sort_img','asc')
                        ->get();
                        $noV = 1;
                        //if($voucher->stat_show == 'y'){
                       // $file2 = DB::table('system_file')
                        //->where('relationId', '=', $voucher->id_main)
                        //->where('relationTable', '=', 'main')
                        //->offset(4)
                        //->orderBy('sort_img','asc')
                        //->first();
                        //}else{
                        $file2 = DB::table('system_file')
                        ->where('relationId', '=', $voucher->id_main)
                        ->where('relationTable', '=', 'main')
                        ->offset(4)
                        ->orderBy('sort_img','asc')
                        ->first();

                        //}
                    @endphp
                    @if(count($file) < 3) @php $count_end=3-count($file); @endphp @for($i=0;$i<3-count($file);$i++)
                        <div
                                class="col-6 col-md-6" style="padding:0;">
                            <a data-fancybox="gallerys" href="{{ URL::asset('img/voucherdetail/img23.png') }}">
                                <div class="controlimgboxvoucherdetail">
                                    <img class="imgbox03" src="{{ URL::asset('img/voucherdetail/img23.png') }}">
                                </div>
                            </a>
                        </div>
                    @endfor
                    @else
                        @foreach($file as $key => $rowFile)
                            <div class="col-6 col-md-6" style="padding:0;">
                                <a data-fancybox="gallerys" href="{{url('storage/main/'.$rowFile->name)}}">
                                    <div class="controlimgboxvoucherdetail">
                                        <img class="imgbox03" src="{{url('storage/main/'.$rowFile->name)}}">
                                    </div>
                                </a>
                            </div>
                            @php $noV++; @endphp
                        @endforeach

                    @endif


                    <div class="col-6 col-md-6" style="padding:  0px;background-color: black;">
                        <a data-fancybox="gallerys"
                           href="@if(!$file2) {{ URL::asset('img/voucherdetail/img23.png') }} @else {{url('storage/main/'.$file2->name)}}  @endif">
                            <div class="seeallphoto">SEE ALL PHOTO</div>
                            <div>
                                <img class="blackopacity imgbox03"
                                     src=" @if(!$file2) {{ URL::asset('img/voucherdetail/img23.png') }} @else {{url('storage/main/'.$file2->name)}}  @endif">
                            </div>
                        </a>
                    </div>
                    @php
                        $file = DB::table('system_file')
                        ->where('relationId', '=', $voucher->id_main)
                        ->where('relationTable', '=', 'main')
                        ->offset(4)
                        ->limit(100)
                        ->get();
                        $noV = 1;
                    @endphp
                    @foreach($file as $key => $rowFile)
                        <div class="col-6 col-md-6" style="padding: 0px;display:none;">
                            <a data-fancybox="gallerys" href="{{url('storage/main/'.$rowFile->name)}}">
                                <div class="">
                                    <img class="fixhvoucherd d-block w-100"
                                         src="{{url('storage/main/'.$rowFile->name)}}">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                @if(\Session::has('success'))
                    <div class="alert alert-success">
                        <li>{{ \Session::get('success') }}</li>
                    </div><br/>
                @endif
                @if(\Session::has('danger'))
                    <div class="alert alert-danger">
                        <li>{{ \Session::get('danger') }}</li>
                    </div><br/>
                @endif
                <div class="headtext top2rem">{{ $voucher->name_main }}</div>
                {{--<div class="underheadtext">{!! str_replace('|',' / ',$voucher->type_blog) !!}</div>--}}
                <div class="travelnormaltext border-bottom">{{ $voucher->address_main }}</div>

                <div class="normaltext top0rem">
                    {{--<h5>{{ $voucher->name_blog }}</h5>--}}
                    {!! $voucher->detail_main !!}
                </div>
                @if($voucher->link_main != null && $voucher->link_main != '' && $voucher->link_main != '-')
                    <a class="btn btn-md btn"
                       href="{{(!preg_match('/http/',$voucher->link_main) ? 'http://'.$voucher->link_main:$voucher->link_main ) }}"
                       role="button" target="_blank"
                       style="background-color: #488BF8;color: white;font-family: kanit;position:  absolute;bottom: 20px;right: 20px;">
                        ดูรายละเอียดเพิ่มเติม</a>
                @endif
                <div class="bottravel"></div>
                <div class="border-bottom"></div>
            </div>
        </div>
        @php
            $vouchers_main = DB::table('tb_voucher')
            ->where('voucher_id','=',$voucher->voucher_id)
            ->where('status_voucher', 'show')
            ->get();
            $percen1 = salePercen($voucher->price_agent,$voucher->sale);
        @endphp
        @foreach ($vouchers_main as $key => $voucher_main)
            @php
                if($voucher_main->status_countdown == 'post'){
                                            $statC= 'post';
                                            $dateEndCountdown = date_format(date_create($voucher_main->date_open), "F j, Y H:i:s");
                                            }else{
                                              $statC= 'sale';
                                            $dateEndCountdown =  date_format(date_create($voucher_main->date_close), "F j, Y H:i:s");
                                            }
            @endphp
            <div class="row">
                <div class="col-8">
                    <div class="miniheadtext top1rem text-left">
                        <div class="top-voucher">
                            <label class="txt-top"><i class="fa fa-pencil"></i> {{ $voucher_main->name_voucher }}
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-4">
                    <div class="miniheadtext top1rem text-right">รายละเอียด</div>
                </div>
            </div>

            <div class="row top1rem box voucherDetailRow" data-id="{{$voucher_main->voucher_id}}">
                <div class="col-12 col-md-4 col-xl-4" style="padding:  0px;">
                    @if($voucher_main->img_show != null)
                        <a data-fancybox="gallerysss" href="{{url('storage/voucher/'.$voucher_main->img_show)}}">
                            <div class="controlimgboxvoucherbig">

                                <img class="imgbox03" src="{{ url('storage/voucher/'.$voucher_main->img_show) }}">
                            </div>
                        </a>





                    @else
                        <a data-fancybox="gallerysss" href="{{ url('img/voucherbrowsing/img23.png') }}">
                            <div class="controlimgboxvoucher">
                                <img class="imgbox" src="{{ url('img/voucherbrowsing/img23.png') }}">
                            </div>
                        </a>
                    @endif
                    @php
                        $file = DB::table('system_file')
                        ->where('relationId', '=', $voucher_main->voucher_id)
                        ->where('relationTable', '=', 'voucher')
                        ->orderBy('sort_img','asc')
                        ->get();
                        $noV = 1;
                    @endphp
                    <div class="row">
                        @php
                            $noV = 1;
                        @endphp
                        @foreach($file AS $rowFile)
                            <div class="col"
                                 style="{{ ($noV == 1 ?'padding-right: 0;': (5 === $noV ?'padding-left: 0;':'padding: 0;')) }} {{ $noV > 5 ? 'display:none' : '' }}">
                                <a data-fancybox="gallerysss" href="{{url('storage/voucher/'.$rowFile->name)}}">
                                    <div class="controlimgboxvoucherde">
                                        <img class="imgbox02" src="{{url('storage/voucher/'.$rowFile->name)}}">
                                    </div>
                                </a>

                            </div>
                            @php $noV++; @endphp
                        @endforeach
                        {!! addCol(count($file)) !!}

                    </div>
                </div>
                <div class="col-12 d-block d-sm-none">
                    @if($percen1 > 0)
                        <div class="distextvouchd">{{ $percen1 }} % OFF</div>
                    @endif
                    @if($voucher_main->qty_voucher > 0)
                        <div class="daylifetext2">

                            <script>
                                countdownTime('{{ $dateEndCountdown }}', '{{ $voucher_main->voucher_id }}', '{{$statC}}',{{$voucher_main->voucher_id}});
                            </script>
                            <span id="timeCountdownWaiting{{ $voucher_main->voucher_id }}">เปิดขายในอีก <br></span>
                            <span id="timeCountdownD{{ $voucher_main->voucher_id }}"></span>
                            <span id="timeCountdownT{{ $voucher_main->voucher_id }}"> days </span>
                            <span id="timeCountdownH{{ $voucher_main->voucher_id }}"></span>
                        </div>
                    @else

                        @php
                            $checkType = \DB::table('type_vouchers')->where('code_type',@$voucher->code_type)->first();
                        @endphp
                        @if($voucher_main->type_voucher == 'in')
                            <div class="alertExpired">
                                @if(@$checkType->type_show == 'single' && $voucher_main->voucher_id == 1167 )
                                    <span>ลุ้นรับฟรี!!</span>
                                @else
                                    <span>หมดแล้ว!!</span>
                                @endif
                            </div>
                        @endif

                    @endif
                </div>
                @php
                    $facilitiesCount = DB::table('tb_facilities')
                               ->whereIn('id_facilities', explode(',',$voucher->relation_facilityid))->count();
                @endphp
                <div class="col-12 col-md-6 col-xl-6">

                    @if($facilitiesCount > 0)
                        <div class="jrminiheadtext top1rem" style="color:  #565656;">สิ่งอำนวยความสะดวก</div>
                        <div class="row">
                            <div class="col-6 col-md-4">
                                @php
                                    $facilities = DB::table('tb_facilities')
                                    ->whereIn('id_facilities', explode(',',$voucher->relation_facilityid))
                                    ->limit(5)
                                    ->get();
                                @endphp
                                @foreach ($facilities as $facilitie)
                                    <div class="travelnormaltext top0rem" style="font-size:12px;"><img
                                                src="{{url('storage/facilities/'.$facilitie->icon_facilities)}}"
                                                width="23"
                                                height="23">&nbsp; {{ $facilitie->name_facilities }}
                                    </div>

                                @endforeach
                            </div>
                            <div class="col-6 col-md-4">
                                @php
                                    $facilities = DB::table('tb_facilities')
                                    ->whereIn('id_facilities', explode(',',$voucher->relation_facilityid))
                                    ->offset(5)
                                    ->limit(5)
                                    ->get();
                                @endphp
                                @foreach ($facilities as $facilitie)
                                    <div class="travelnormaltext top0rem" style="font-size:12px;"><img
                                                src="{{url('storage/facilities/'.$facilitie->icon_facilities)}}"
                                                width="23"
                                                height="23">&nbsp; {{ $facilitie->name_facilities }}
                                    </div>

                                @endforeach
                            </div>
                            <div class="col-4 col-md-4 hidden-md-down">
                                @php
                                    $facilities = DB::table('tb_facilities')
                                    ->whereIn('id_facilities', explode(',',$voucher->relation_facilityid))
                                    ->offset(10)
                                    ->limit(5)
                                    ->get();
                                @endphp
                                @foreach ($facilities as $facilitie)
                                    <div class="travelnormaltext top0rem" style="font-size:12px;"><img
                                                src="{{url('storage/facilities/'.$facilitie->icon_facilities)}}"
                                                width="23"
                                                height="23">&nbsp; {{ $facilitie->name_facilities }}
                                    </div>

                                @endforeach
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                    @endif
                    @if($voucher->qty_customer !='' )

                        <div class="jrminiheadtext top1rem" style="color:  #565656;">จำนวนผู้เข้าพักได้สูงสุด</div>
                        <div class="travelnormaltext ">ผู้ใหญ่ {{ $voucher->qty_customer }} </div>
                        <div class="border-bottom"></div>

                    @endif

                    @if($voucher->qty_night !='' )
                        <div class="jrminiheadtext top1rem" style="color:  #565656;">วันเข้าพัก</div>
                        <div class="travelnormaltext ">จำนวน {{ $voucher->qty_night }}</div>
                    @endif

                    @if($voucher->name_extra !='' && $voucher->detail_extra !='')
                        <div class="jrminiheadtext top1rem" style="color:  #565656;">{{ $voucher->name_extra }}</div>
                        <div class="travelnormaltext ">{!! $voucher->detail_extra !!}</div>
                        <div class="border-bottom"></div>

                    @endif
                    @if($voucher->title_voucher !='')
                        <div class="">
                            <div class="jrminiheadtext top1rem" style="color:  #565656;">Title Voucher</div>
                            <div class="travelnormaltext ">{!! ($voucher->title_voucher); !!}</div>
                            <div class="border-bottom"></div>

                        </div>
                    @endif
                    @if($voucher->term_voucher !='')
                        {{-- <div class="hide999 hide"> --}}
                        <div class="">
                            <div class="jrminiheadtext top1rem" style="color:  #565656;">เงื่อนไขการใช้ Voucher</div>
                            <div class="travelnormaltext termtext">
                                {!! ($voucher->term_voucher) !!}
                            </div>

                        </div>
                    @endif
                </div>
                <div class="col-12 col-md-2 col-xl-2 d-none d-sm-block">
                    @if($percen1 > 0)
                        <div class="distextvouchd">{{ $percen1 }}% OFF</div>
                    @endif
                    @if($voucher->qty_voucher > 0)
                        <div class="daylifetext2">
                            <script>
                                countdownTime('{{ $dateEndCountdown }}', 'y{{ $voucher_main->voucher_id }}', '{{$statC}}', '{{$voucher_main->voucher_id}}');
                            </script>
                            <span id="timeCountdownWaitingy{{ $voucher_main->voucher_id }}">เปิดขายในอีก<br></span>
                            <span id="timeCountdownDy{{ $voucher_main->voucher_id }}">00</span>
                            <span id="timeCountdownTy{{ $voucher_main->voucher_id }}"> days </span>
                            <span id="timeCountdownHy{{ $voucher_main->voucher_id }}">00:00:00</span>

                        </div>
                    @else
                        @php
                            $checkType = \DB::table('type_vouchers')->where('code_type',@$voucher->code_type)->first();
                        @endphp
                        @if($voucher_main->type_voucher == 'in')
                            <div class="alertExpired">
                                @if(@$checkType->type_show == 'single' && $voucher_main->voucher_id == 1167 )
                                    <span>ลุ้นรับฟรี!!</span>
                                @else
                                    <span>หมดแล้ว!!</span>
                                @endif
                            </div>
                        @endif

                    @endif
                </div>
                {{-- <div class="col-12">
                    @if($voucher->term_voucher !='')

                        <a class="btn btn-block read-more999" onclick="read_more(999)" role="button"
                           style="background-color: #fbfbfb;color: red;font-family: Roboto;font-size:  16px;">
                            <b>กดเพื่อดูรายละเอียดเพิ่มเติม</b></a>
                    @endif
                    <a class="btn btn-block read-less read-less999" onclick="read_less(999)" role="button"
                       style="background-color: #fbfbfb;color: red;font-family: Roboto;font-size:  16px;">
                        <b>กดเพื่อย่อรายละเอียด</b></a>
                    <div class="bottravel"></div>
                </div> --}}
                @php
                    // $total_sales = DB::table('view_voucher_total')->where('voucher_id',$voucher->voucher_id)->first();
                     $orders = App\Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
                                    ->where('voucher_id','=',$voucher->voucher_id)
                                    ->where(function ($query) {
                                        $query->where('status_order', '=', '000')
                                            ->orWhere('status_order', '=', '001');
                                    })
                                    // ->where('status_order','=','000')
                                    // ->orWhere('status_order','=','002')
                                    ->count();
                @endphp
                <div class="col-md-10 dealposition">
                    @if($voucher->type_voucher == 'in')
                        @if($voucher->show_sale_v =='y')
                            ขายไปแล้ว {{$orders}} ดีล <br>
                        @endif
                        สต๊อค {{$voucher->qty_voucher}} ใบ
                    @endif
                </div>
                <div class="col-12 offset-md-10 col-md-2">
                    @if($voucher->sale > 0)

                        <div class="positionbaht01 bahttext text-right"
                             style="text-decoration: line-through;color: #707070;">฿
                            {{  number_format($voucher->price_agent,0,".",",") }}
                        </div>
                    @endif
                    <div class="positionbaht02 bahttext text-right" style="color: red;font-weight: 600;">
                        ฿ {{ number_format($voucher->price_sale,0,".",",")  }}</div>
                    @php
                        $today = Date('Y-m-d H:i:s');

                        // echo ($voucher->voucher_id.' = '.$orders);
                    @endphp
                    @if($voucher->status_countdown == 'post')
                        <p class="btnroom" style="color:green;font-size:20px;">
                            <marquee>Coming soon...</marquee>
                        </p>

                    @else
                        @if ($voucher->date_close < $today)
                            <p class="btnroom" style="color:red;font-size:20px;">หมดเวลาการขาย</p>
                        @elseif($voucher->type_voucher == 'out')
                            @if($voucher->link_voucher_contact != '')
                                <a class="btn btn-md btn-block btnroom addToCart clickLog"
                                   data-id="{{$voucher->voucher_id}}"
                                   data-value="{{ $voucher->price_sale }}" target="_blank"
                                   href="{{(!preg_match('/http/',$voucher->link_voucher_contact) ? 'http://'.$voucher->link_voucher_contact:$voucher->link_voucher_contact ) }}"
                                   role="button" style="background-color: #488BF8;color: white;">
                                    {{btnBooking($voucher->code_type)}}
                                </a>
                            @else
                                <a class="btn btn-md btn-block btnroom addToCart clickLog"
                                   data-id="{{$voucher->voucher_id}}"
                                   data-value="{{ $voucher->price_sale }}"
                                   data-tel="{{$voucher->tel_voucher_contact }}"
                                   href="javascript:void(0)"
                                   onclick="modalTel('{{$voucher->tel_voucher_contact }}')"
                                   role="button" style="background-color: #488BF8;color: white;">
                                    {{btnBooking($voucher->code_type)}}
                                </a>
                            @endif
                        @elseif($voucher->qty_voucher <=   0)
                            <p class="btnroom" style="color:red;font-size:20px;">จำนวนดีลหมด</p>
                        @else
                            <a class="btn btn-md btn-block btnroom addToCart"
                               data-value="{{ $voucher->price_sale }}"
                               data-id="{{$voucher->voucher_id}}"
                               href=" {{ url('/add-to-cart',$voucher->voucher_id) }}"
                               role="button" style="background-color: #488BF8;color: white;">
                                {{btnBooking($voucher->code_type)}}
                            </a>
                        @endif
                    @endif
                    <div class="bottravel d-xl-none"></div>
                </div>
            </div>
        @endforeach

        @foreach($voucherGroup AS $key1 => $rowVoucher)
            @php
                $percen2 = salePercen($rowVoucher->price_agent,$rowVoucher->sale);
                $dateExpired = strtotime($rowVoucher->date_close);
                $thisTime = time();
                $totalExpired = time() - $dateExpired;
                $dayFive = 86400 * 5;
                if($rowVoucher->status_countdown == 'post' ){
                            $dateEndCountdown = date_format(date_create($rowVoucher->date_open), "F j, Y H:i:s");
                            $statC= 'post';
                            }else{
                             $statC= 'sale';
                            $dateEndCountdown =  date_format(date_create($rowVoucher->date_close), "F j, Y H:i:s");
                            }
            @endphp
            {{--@if(($dayFive >= $totalExpired && $rowVoucher->expired != 'y') || ($dayFive <= $totalExpired && $rowVoucher->expired--}}
            {{--== 'y') || ($dayFive >= $totalExpired) )--}}
            <div class="row">
                <div class="col-8">
                    <div class="miniheadtext top1rem text-left">
                        <div class="top-voucher">
                            <label class="txt-top"><i class="fa fa-pencil"></i> {{ $rowVoucher->name_voucher }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="miniheadtext top1rem text-right">รายละเอียด</div>
                </div>
            </div>
            <div class="row top1rem box voucherDetailRow" data-id="{{$rowVoucher->voucher_id}}">
                <div class="col-12 col-md-4 col-xl-4" style="padding:  0px;">
                    @if($rowVoucher->img_show != null)
                        <a data-fancybox="gallery{{$key1}}"
                           href="{{url('storage/voucher/'.$rowVoucher->img_show)}}">
                            <div class="controlimgboxvoucherbig">
                                <img class="imgbox03" src="{{ url('storage/voucher/'.$rowVoucher->img_show) }}">
                            </div>
                        </a>



                    @else
                        <a data-fancybox="gallery{{$key1}}" href="{{ url('img/voucherbrowsing/img23.png') }}">
                            <img class="img-fluid d-block w-100 " src="{{ url('img/voucherbrowsing/img23.png') }}">
                        </a>
                    @endif
                    @php
                        $file = DB::table('system_file')
                        ->where('relationId', '=', $rowVoucher->voucher_id)
                        ->where('relationTable', '=', 'voucher')
                        ->orderBy('sort_img','asc')
                        ->get();
                        $noV = 1;
                    @endphp
                    <div class="row">
                        @php
                            $noV = 1;
                        @endphp
                        @foreach($file AS $rowFile)
                            <div class="col"
                                 style="{{ ($noV == 1 ?'padding-right: 0;': (5 === $noV ?'padding-left: 0;':'padding: 0;')) }} {{ $noV > 5 ? 'display:none' : '' }}">
                                <a data-fancybox="gallery{{$key1}}"
                                   href="{{url('storage/voucher/'.$rowFile->name)}}">
                                    <div class="controlimgboxvoucherde">
                                        <img class="imgbox02" src="{{url('storage/voucher/'.$rowFile->name)}}">
                                    </div>
                                </a>
                            </div>
                            @php $noV++; @endphp
                        @endforeach
                        {!! addCol(count($file)) !!}

                    </div>
                </div>
                <div class="col-12 d-block d-sm-none">
                    @if($percen2 > 0)
                        <div class="distextvouchd">{{  $percen2 }} % OFF</div>
                    @endif
                    @if($rowVoucher->qty_voucher > 0)
                        <div class="daylifetext2">
                            <script>
                                countdownTime('{{ $dateEndCountdown }}', 'x{{ $rowVoucher->voucher_id }}', '{{$statC}}',{{$rowVoucher->voucher_id}});
                            </script>
                            <span id="timeCountdownWaiting{{ 'x'.$rowVoucher->voucher_id }}">เปิดขายในอีก<br></span>
                            <span id="timeCountdownDx{{ $rowVoucher->voucher_id }}">00</span>
                            <span id="timeCountdownTx{{ $rowVoucher->voucher_id }}"> days </span>
                            <span id="timeCountdownHx{{ $rowVoucher->voucher_id }}">00:00:00</span>
                        </div>
                    @else
                        @php
                            $checkType = \DB::table('type_vouchers')->where('code_type',@$rowVoucher->code_type)->first();
                        @endphp
                        @if($rowVoucher->type_voucher == 'in')
                            <div class="alertExpired">
                                @if(@$checkType->type_show == 'single' && $rowVoucher->voucher_id == 1167 )
                                    <span>ลุ้นรับฟรี!!</span>
                                @else
                                    <span>หมดแล้ว!!</span>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
                <div class="col-12 col-md-6 col-xl-6">
                    @php
                        $facilitiesCount = DB::table('tb_facilities')
                                     ->whereIn('id_facilities' , explode(',',$rowVoucher->relation_facilityid))
                                     ->count();
                    @endphp
                    @if($facilitiesCount > 0)
                        <div class="jrminiheadtext top1rem" style="color:  #565656;">สิ่งอำนวยความสะดวก</div>
                        <div class="row">
                            <div class="col-6 col-md-4">
                                @php
                                    $fac = DB::table('tb_facilities')
                                    ->whereIn('id_facilities' , explode(',',$rowVoucher->relation_facilityid))
                                    ->limit(5)
                                    ->get();
                                    foreach ($fac AS $facVou){
                                    echo '<div class="travelnormaltext top0rem" style="font-size:12px;"><img width="25" height="25"
                                            src="'. url('storage/facilities/'.$facVou->icon_facilities)  .'">&nbsp; '.
                                        $facVou->name_facilities .'
                                    </div>';
                                    }
                                @endphp
                            </div>
                            <div class="col-6 col-md-4">
                                @php
                                    $fac = DB::table('tb_facilities')
                                    ->whereIn('id_facilities' , explode(',',$rowVoucher->relation_facilityid))
                                    ->offset(5)
                                    ->limit(5)
                                    ->get();
                                    foreach ($fac AS $facVou){
                                    echo '<div class="travelnormaltext top0rem" style="font-size:12px;"><img width="25" height="25"
                                            src="'. url('storage/facilities/'.$facVou->icon_facilities)  .'">&nbsp; '.
                                        $facVou->name_facilities .'
                                    </div>';
                                    }
                                @endphp
                            </div>
                            <div class="col-4 col-md-4 hidden-md-down">
                                @php
                                    $fac = DB::table('tb_facilities')
                                    ->whereIn('id_facilities' , explode(',',$rowVoucher->relation_facilityid))
                                    ->offset(10)
                                    ->limit(5)
                                    ->get();
                                    foreach ($fac AS $facVou){
                                    echo '<div class="travelnormaltext top0rem" style="font-size:12px;"><img width="25" height="25"
                                            src="'. url('storage/facilities/'.$facVou->icon_facilities)  .'">&nbsp; '.
                                        $facVou->name_facilities .'
                                    </div>';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                    @endif
                    @if($rowVoucher->qty_customer !='' )
                        <div class="jrminiheadtext top1rem" style="color:  #565656;">จำนวนผู้เข้าพักได้สูงสุด</div>
                        <div class="travelnormaltext ">ผู้ใหญ่ {{ $rowVoucher->qty_customer }} </div>
                    @endif

                    @if($rowVoucher->qty_night !='')
                        <div class="jrminiheadtext top1rem" style="color:  #565656;">วันเข้าพัก</div>
                        <div class="travelnormaltext ">จำนวน {{ $rowVoucher->qty_night }}</div>
                        <div class="border-bottom"></div>
                    @endif

                    @if($rowVoucher->name_extra !='' && $rowVoucher->detail_extra !='')
                        <div class="jrminiheadtext top1rem"
                             style="color:  #565656;">{{ $rowVoucher->name_extra }}</div>
                        <div class="travelnormaltext ">{!! $rowVoucher->detail_extra !!}</div>
                        <div class="border-bottom"></div>
                    @endif

                    @if($rowVoucher->title_voucher !='')
                        <div class="">
                            <div class="jrminiheadtext top1rem" style="color:  #565656;">Title Voucher
                            </div>
                            <div class="travelnormaltext ">{!! ($rowVoucher->title_voucher); !!}</div>
                            <div class="border-bottom"></div>

                        </div>
                    @endif
                    @if($rowVoucher->term_voucher !='')
                        {{-- <div class="hide{{$key}} hide"> --}}
                        <div class="">
                            {{--                                <div class="border-bottom"></div>--}}
                            <div class="jrminiheadtext top1rem" style="color:  #565656;">เงื่อนไขการใช้ Voucher
                            </div>
                            <div class="travelnormaltext termtext">{!! ($rowVoucher->term_voucher); !!}</div>
                        </div>
                    @endif
                </div>
                <div class="col-12 col-md-2 col-xl-2 d-none d-sm-block">
                    @if($percen2 > 0)
                        <div class="distextvouchd">{{   $percen2 }}% OFF</div>
                    @endif
                    @if($rowVoucher->qty_voucher > 0)
                        <script>
                            countdownTime('{{ $dateEndCountdown }}', 'z{{ $rowVoucher->voucher_id }}', '{{$statC}}',{{ $rowVoucher->voucher_id }});
                        </script>
                        <div class="daylifetext2">
                            <span id="timeCountdownWaitingz{{ $rowVoucher->voucher_id }}">เปิดขายในอีก <br></span>
                            <span id="timeCountdownDz{{ $rowVoucher->voucher_id }}">00</span>
                            <span id="timeCountdownTz{{ $rowVoucher->voucher_id }}"> days </span>
                            <span id="timeCountdownHz{{ $rowVoucher->voucher_id }}">00:00:00</span>

                        </div>
                    @else
                        @php
                            $checkType = \DB::table('type_vouchers')->where('code_type',@$rowVoucher->code_type)->first();
                        @endphp
                        @if($rowVoucher->type_voucher == 'in')
                            <div class="alertExpired">
                                @if(@$checkType->type_show == 'single'  && $rowVoucher->voucher_id == 1167 )
                                    <span>ลุ้นรับฟรี!!</span>
                                @else
                                    <span>หมดแล้ว!!</span>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
                {{-- <div class="col-12">
                    @if($rowVoucher->term_voucher !='')

                        <a class="btn btn-block read-more{{$key}}" onclick="read_more({{$key}})" role="button"
                           style="background-color: #fbfbfb;color: red;font-family: Roboto;font-size:  16px;">
                            <b>กดเพื่อดูรายละเอียดเพิ่มเติม</b> </a>
                    @endif
                    <a class="btn btn-block read-less read-less{{$key}}" onclick="read_less({{$key}})" role="button"
                       style="background-color: #fbfbfb;color: red;font-family: Roboto;font-size: 16px;">
                        <b>กดเพื่อย่อรายละเอียด</b></a>
                    <div class="bottravel"></div>
                </div> --}}

                @php
                    // $total_sale = DB::table('view_voucher_total')->where('voucher_id',$rowVoucher->voucher_id)->first();
                       $orders = App\Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
                                    ->where('voucher_id','=',$rowVoucher->voucher_id)
                                    ->where(function ($query) {
                                        $query->where('status_order', '=', '000')
                                            ->orWhere('status_order', '=', '001');
                                    })
                                    // ->where('status_order','=','000')
                                    // ->orWhere('status_order','=','002')
                                    ->count();
                @endphp
                <div class="col-md-10 dealposition">
                    @if($rowVoucher->type_voucher == 'in')

                        @if($rowVoucher->show_sale_v == 'y')
                            ขายไปแล้ว {{$orders}} ดีล<br>
                        @endif
                        สต๊อค {{$rowVoucher->qty_voucher}} ใบ
                    @endif
                </div>
                <div class="col-md-2">
                    @if($rowVoucher->sale > 0)
                        <div class="positionbaht01 bahttext text-right"
                             style="text-decoration: line-through;color: #707070;">฿
                            {{  number_format($rowVoucher->price_agent,0,".",",") }}
                        </div>
                    @endif

                    <div class="positionbaht02 bahttext text-right" style="color: red;font-weight: 600;">
                        ฿ {{ number_format($rowVoucher->price_sale,0,".",",")  }}</div>
                    @php
                        $today = Date('Y-m-d H:i:s');
                        $orders = App\Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
                                    ->where('voucher_id','=',$rowVoucher->voucher_id)
                                    ->where(function ($query) {
                                        $query->where('status_order', '=', '000')
                                            ->orWhere('status_order', '=', '002');
                                    })
                                    // ->where('status_order','=','000')
                                    // ->orWhere('status_order','=','002')
                                    ->count();
                        // echo ($rowVoucher->voucher_id.' = '.$orders);
                    @endphp
                    @if($rowVoucher->status_countdown == 'post')
                        <p class="btnroom" style="color:green;font-size:20px;">
                            <marquee>Coming soon...</marquee>
                        </p>

                    @else
                        @if ($rowVoucher->date_close < $today)
                            <p class="btnroom" style="color:red;font-size:20px;">หมดเวลาการขาย</p>
                        @elseif($rowVoucher->type_voucher == 'out')
                            @if($rowVoucher->link_voucher_contact != '')
                                <a class="btn btn-md btn-block btnroom addToCart clickLog"
                                   data-id="{{$rowVoucher->voucher_id}}"
                                   data-value="{{ $rowVoucher->price_sale }}" target="_blank"
                                   href="{{(!preg_match('/http/',$rowVoucher->link_voucher_contact) ? 'http://'.$rowVoucher->link_voucher_contact:$rowVoucher->link_voucher_contact ) }}"
                                   role="button" style="background-color: #488BF8;color: white;">
                                    {{btnBooking($rowVoucher->code_type)}}
                                </a>
                            @else
                                <a class="btn btn-md btn-block btnroom addToCart clickLog"
                                   data-id="{{$rowVoucher->voucher_id}}"
                                   data-value="{{ $rowVoucher->price_sale }}"
                                   data-tel="{{$rowVoucher->tel_voucher_contact }}"
                                   href="javascript:void(0)"
                                   onclick="modalTel('{{$rowVoucher->tel_voucher_contact }}')"
                                   role="button" style="background-color: #488BF8;color: white;">
                                    {{btnBooking($rowVoucher->code_type)}}
                                </a>
                            @endif
                        @elseif($rowVoucher->qty_voucher <=   0)
                            <p class="btnroom" style="color:red;font-size:20px;">จำนวนดีลหมด</p>
                        @else
                            <a class="btn btn-md btn-block btnroom addToCart"
                               data-value="{{ $rowVoucher->price_sale }}"
                               data-id="{{$rowVoucher->voucher_id}}"
                               href=" {{ url('/add-to-cart',$rowVoucher->voucher_id) }}"
                               role="button" style="background-color: #488BF8;color: white;">
                                {{btnBooking($rowVoucher->code_type)}}
                            </a>
                        @endif
                    @endif


                </div>
                <div class="bottravel d-xl-none"></div>


            </div>
            {{--@endif--}}

        @endforeach
        <div class="border-bottom"></div>
        <div class="miniheadtext top2rem">ข้อมูลเพิ่มเติม</div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="jrminiheadtext top1rem">ที่อยู่</div>
                <div class="normaltext top0rem">{!! $voucher->address_main !!}</div>
                <div class="jrminiheadtext top1rem">เวลาเปิดทำการ</div>
                <div class="normaltext top0rem">{{ $voucher->time_main }}</div>
            </div>
            <div class="col-12 col-md-6">
                <div class="jrminiheadtext top1rem">ติดต่อ</div>
                <div class="normaltext top0rem">{{ $voucher->tel_main }}‬</div>
                <div class="jrminiheadtext top1rem">ราคา</div>
                <div class="normaltext top0rem">{{ $voucher->price_main }} ต่อคืน</div>
            </div>
        </div>


        <div id="share">
        </div>

        <div class="border-bottom"></div>

        <div class="miniheadtext top2rem">ดีลอื่นๆ ที่กําลังมีคนสนใจ</div>

        <div class="row mx-auto">
            @foreach ( $sorting_voucher as $sorting)
                <div class="col-sm-12 col-md-3 paddingtravel top1rem ">
                    <a href="{{ url('voucherdetail',$sorting->voucher_id) }}">
                        <div class="controlimgbox">
                            <img src="{{ url('storage/voucher/'.$sorting->img_show) }}" class="img-fluid ">
                        </div>
                        <div class="minitraveltext top0rem" style="color: #707070;">
                            <strong>{{ $sorting->name_main }}</strong></div>
                        <div class="minitraveltext" style="color: #403d3deb;">{{ $sorting->name_voucher }}</div>
                        <div class="minitraveltext" style="color: #707070;">{{ $sorting->address_main }}</div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>


    <div class="modal" tabindex="-1" id="modalTel" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><b>Tel.</b><span id="txtTel"></span></p>
                </div>
                <div class="modal-footer">
                    <a href="tel:" id="telLink" class="btn btn-primary">
                        Call <i class="fa fa-phone" aria-hidden="true"></i></a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        function modalTel(tel) {
            $('#txtTel').html(tel);
            $('#telLink').attr("href", "tel:" + tel);
            $('#modalTel').modal("show");
        }

        $('.read-less, .hide').hide();

        function read_more(n) {
            $('.read-more' + n + ', .hide' + n + '').hide();
            $('.read-less' + n + ', .hide' + n + '').toggle();

        }

        function read_less(n) {
            $('.read-more' + n + ', .hide' + n + '').toggle();
            $('.read-less' + n).hide();
            $('.hide' + n).hide();
        }

        // $('.read-more').click(function () {
        //     $(this,'.read-less, .hide').show();
        // $(this,'.hide')()toggle();
        // $('.read-more, .read-less, .hide').toggle();
        // });
        // $('.read-more, .read-less').click(function () {
        // $(this,'.hide')()toggle();
        // $('.read-more, .read-less, .hide').toggle();
        // });
    </script>
    <script>
        $('.disabled').click(function (e) {
            e.preventDefault();
        });
    </script>

    <script>
        $("#share").jsSocials({
            showCount: false,
            showLabel: false,
            shareIn: "popup",
            shares: ["facebook", "twitter"],
        });
    </script>
    <script>
        $(".vdo iframe").addClass('fixhvoucherd d-block w-100');
    </script>

    <script>
        $('.clickLog').click(function () {
            var id = $(this).attr('data-id');
            var val = $(this).attr('data-value')
            $.ajax({
                url: '/voucherdetail/click_voucher/' + id,
                type: 'GET',
                success: function (res) {
                    if (res == 'ok') {
                        console.log('click log success')
                    }
                }
            })
        });

        $('.addToCart').click(function () {
            var id = $(this).attr('data-id');
            var val = $(this).attr('data-value')
            fbq('track', 'AddToCart', {content_ids: [id], content_type: 'product', currency: 'THB', value: val})
        });

    </script>

    <script>
        $(document).ready(function () {
            var voucherDetailRow = $('.voucherDetailRow');
            var arrId = []
            $.each(voucherDetailRow, function (key, ele) {
                var id = $(ele).attr('data-id');
                arrId.push(id)
            });
            fbq('track', 'ViewContent', {content_ids: arrId, content_type: 'product'})
        });

    </script>
@endsection