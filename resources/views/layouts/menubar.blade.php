<link rel="stylesheet" href="{{ URL::asset('css/css.css') }}">
@php
    $logo = DB::table('system_file')->where('relationTable','=','logo')->first();
@endphp
@php
    $checkVoucher = \App\Orders::query()->select('d.voucher_id','d.qty','tb_order.created_at','d.odt_id')
    ->join('tb_order_detail AS d','tb_order.id','d.order_id')
    ->where('tb_order.created_at','>','2020-07-10')
    ->where('tb_order.status_order','999')->where('d.refund_stock','no')
    ->whereRaw("TIMESTAMPDIFF(MINUTE,tb_order.created_at,'".date('Y-m-d H:i:s')."') > 60")->get();

foreach ($checkVoucher AS $v){
    \App\Orders::query()->where('id',$v->id)->update(['status_order'=>'999']); 
\App\Order_details::query()->where('odt_id',$v->odt_id)->update(['refund_stock' => 'yes']);
\App\Model\admin\VoucherModel::query()->where('voucher_id',$v->voucher_id)->update(['qty_voucher' => DB::raw('qty_voucher + '.$v->qty)]);
}
@endphp
<style>
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 300px;
        width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: rgba(0, 0, 0, 0.5);
    }

    .dropdown-content li:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .type-text {
        position: absolute;
        top: 20px;
        right: -1px;
        /*background-color: #e1fdff;*/
        /*padding: 7px 20px 5px 20px;*/
        font-family: 'Kanit', sans-serif;
        /*font-weight: 400;*/
        /*font-size: 23px;*/
        z-index: 20;
        /*border:solid 1px black;*/
    }

    .type-text img {
        width: 130px;
        height: 45px;
    }

    .termtext {
        margin-bottom: 40px;
    }

    .daylifetext {
        width: 150px;
    }

    @media (max-width: 767px) and (min-width: 100px) {
        .txtCountdown {
            position: absolute;
            top: 60px;
            left: 0px;
            z-index: 25;
            background-color: #FBDC07;
            padding: 7px 15px 5px 15px;
            font-family: roboto;
            font-weight: 600;
            font-size: 15px;
            color: black;
            width: 150px;
        }

        .daylifetext {
            display: none;
        }

        .termtext {
            margin-bottom: 40px;
        }

    }


    @media (max-width: 424px) and (min-width: 414px) {
        .type-text {
            position: absolute;
            top: 10px;
            right: -1px;
            /*background-color: #e1fdff;*/
            /*padding: 7px 20px 5px 20px;*/
            font-family: roboto;
            /*font-weight: 400;*/
            /*font-size: 18px;*/
            /*border:solid 1px black;*/

        }

        .type-text img {
            width: 90px;
            height: 33px;
        }

        .daylifetext {
            position: absolute;
            top: -280px;
            left: 0px;
            background-color: #FBDC07;
            padding: 7px 15px 5px 15px;
            font-family: roboto;
            font-weight: 600;
            font-size: 15px;
            width: 170px;

        }

        .termtext {
            margin-bottom: 40px;
        }
    }

    .sticky-top {
        z-index: 10 !important;
    }

</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">

    <a class="navbar-brand " href="{{url('voucherbrowsing')}}" style="

    

    padding-bottom: 0px;

    margin-bottom: 10px;

    font-size: 22px;

    font-family:  kanit;

">

        <img src="{{ asset('storage/logo').'/'.$logo->name }}" width="100" height="45"/>
    </a>


    <div class="">

        <a href="{{ url('cart') }}" role="button" style=""><img src="{{ URL::asset('img/shoppingcart.png')  }}"
                                                                class="navcart" alt="Responsive image" style="

    height:  30px;

    width:  30px;

    right: 95px;

    top: 13px;

    position: absolute;

">

            <span class="coutnumbernav" id=""
                  style="display: block;">@if (Cart::count() != 0) {{ Cart::count()  }} @else 0 @endif</span>


        </a>

    </div>


    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"

            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

    </button>


    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="nav navbar-nav mr-auto" style="font-family:  kanit;">

            <li id="voucher" class="nav-item">

                <a class="nav-link mebunartext underline" href="{{url('voucherbrowsing')}}">ดีล</a>

            </li>
            <li id="typeDeal" class="nav-item dropdown">
                <a class="nav-link mebunartext" href="#">ประเภทดีล</a>
                <ul class="dropdown-content  nav navbar-nav">
                    @php
                        $location = \DB::table('type_vouchers')->where('type_show','multiple')->get();
                    @endphp
                    @foreach($location AS $row)
                        <li class="nav-item"><a class="mebunartext"
                                                href="{{url('deal',$row->name_type)}}">{{$row->name_type}}</a>
                        </li>
                    @endforeach
                    {{--<li class="nav-item"><a class="mebunartext" href="{{url('destination')}}">500 Rai Floating Resort</a></li>--}}
                    {{--<li class="nav-item"><a class="mebunartext" href="{{url('destination')}}">500 Rai Farmhouse Khaosok</a></li>--}}
                    {{--<li class="nav-item"><a class="mebunartext" href="{{url('destination')}}">Ban Ta Khun by 500 Rai</a></li>--}}
                </ul>
            </li>
            {{--            @if(@Session::get('id_member') == 6935)--}}
            <li id="province" class="nav-item dropdown">
                <a class="nav-link mebunartext" href="#">เลือกจังหวัด</a>
                <ul class="dropdown-content  nav navbar-nav">
                    @php
                        $location = \App\Model\Location::query()->orderByRaw(' CONVERT( name_location USING tis620 ) ','ASC')->get();
                    @endphp
                    @foreach($location AS $row)
                        <li class="nav-item"><a class="mebunartext"
                                                href="{{url('search',$row->name_location)}}">{{$row->name_location}}</a>
                        </li>
                    @endforeach
                    {{--<li class="nav-item"><a class="mebunartext" href="{{url('destination')}}">500 Rai Floating Resort</a></li>--}}
                    {{--<li class="nav-item"><a class="mebunartext" href="{{url('destination')}}">500 Rai Farmhouse Khaosok</a></li>--}}
                    {{--<li class="nav-item"><a class="mebunartext" href="{{url('destination')}}">Ban Ta Khun by 500 Rai</a></li>--}}
                </ul>
            </li>
            {{--@endif--}}
            <li id="pageSingleJourney" class="nav-item">

                <a class="nav-link mebunartext" href="{{ url('singlejourney') }}">เส้นทางคนโสด </a>

            </li>

            <li id="travelguide" class="nav-item">

                <a class="nav-link mebunartext" href="{{ url('travelguide') }}">ข้อมูลท่องเที่ยว </a>

            </li>

            <li id="work" class="nav-item">

                <a class="nav-link mebunartext" href="{{ url('howitwork') }}">วิธีการใช้งาน</a>

            </li>

            <li id="joinus" class="nav-item">

                <a class="nav-link mebunartext" href="{{ url('joinus') }}">ร่วมงานกับเรา</a>

            </li>

        </ul>


        <ul class="nav navbar-nav ml-auto">

            <form autocomplete="off" class="form-inline my-2 my-lg-0 hiddenipad" onsubmit="return checkSearch(1)"
                  method="get">
                {{-- <input class="form-control mr-sm-4" type="search" id="searchTop" name="search" required
                       placeholder="Search"
                       aria-label="Search"> --}}
                <div class="input-group md-form form-sm form-2 pl-0" style="margin-right:13px ">
                    <input autocomplete="off" style="border-right:none" class="form-control my-0 py-1 amber-border "
                           id="searchTop" name="search" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <a style="border-left:none;background-color:#ffffff" onclick="checkSearch(1)"
                           class="input-group-text amber lighten-3" id="basic-text1"><i class="fas fa-search text-grey"
                                                                                        aria-hidden="true"></i></a>
                    </div>
                </div>
            </form>

            <a href="{{ url('/cart') }}" role="button" style="">
                <img src="{{ URL::asset('img/shoppingcart.png')}}" class="navcartpc" alt="Responsive image"
                     style="height:  30px;width:  30px;right: 3px;top: 4px;position: relative;">
                <span class="coutnumbernavpc" id=""
                      style="display: block;">@if (Cart::count() != 0) {{ Cart::count()  }} @else 0 @endif</span>
            </a>
            @if ((Session::get('id_member') != "" ))

                <li id="account" class="nav-item">

                    <a class="nav-link mebunartext" href="{{ url('customeraccount') }}">บัญชีของฉัน</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link mebunartext" href="{{ url('/logout2') }}">ออกจากระบบ</a>

                </li>

            @else

                <li class="nav-item">

                    <a class="nav-link mebunartext" href="{{ url('sign-in') }}">เข้าสู่ระบบ</a>

                </li>

            @endif


            <form autocomplete="off" class="form-inline my-2 my-lg-0 hiddenpc" onsubmit="return checkSearch(2)"
                  method="get">
                {{-- <input class="form-control mr-sm-4" type="search" id="searchTop2" name="search" placeholder="Search"
                       required
                       aria-label="Search"> --}}
                <div class="input-group md-form form-sm form-2 pl-0" style="margin-right:13px ">
                    <input autocomplete="off" style="border-right:none" class="form-control my-0 py-1 amber-border "
                           id="searchTop2" name="search" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <a style="border-left:none;background-color:#ffffff" onclick="checkSearch(2)"
                           class="input-group-text amber lighten-3" id="basic-text1"><i class="fas fa-search text-grey"
                                                                                        aria-hidden="true"></i></a>
                    </div>
                </div>
            </form>


        </ul>


    </div>

</nav>


{{-- {{ Auth::user()->id_member }} --}}
<style>
    .ui-autocomplete {
        max-height: 300px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
        /* add padding to account for vertical scrollbar */
        padding-right: 20px;
    }
</style>
<script>
    $(function () {
        $.ajax({
            url: '/menu/search',
            type: "GET",
            success: function (res) {
                var availableTags = [];
                $.each(res.LOCATION, function (i, v) {
                    availableTags.push(v.name_location);
                });
                $.each(res.MAIN, function (i, v) {
                    availableTags.push(v.name_main);
                });
                $("#searchTop").autocomplete({
                    source: availableTags.sort(),
                    scroll: true
                });
                $("#searchTop2").autocomplete({
                    source: availableTags.sort(),
                    scroll: true
                });
            }
        })


    });

    function checkSearch(id) {
        if (id == 1) {
            search = $('#searchTop').val();
            fbq('track', 'Search', {search_string: search})
        } else {
            search = $('#searchTop2').val();
            fbq('track', 'Search', {search_string: search})

        }
        if (search.length > 2) {
            // alert(search);
            window.location = "/search/" + search + "/";
        }
        return false;
    }

    $(".nav li").each(function (index) {

        $(this).click(function () {
            // $('.nav-item').removeClass('active');
            var currentActive = $(".nav").find("li.active");

            currentActive.removeClass("active");

            $(this).addClass("active");

        });

    });

</script>