@extends('layouts.components')
<style>
    .disabled {
        pointer-events: none;
        cursor: default;
    }
  
  
</style>
@section('contentFront')
    <div id="loader-wrapper">
        <div id="loader"></div>

        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>
    <div class="container">
        {!! stepCart(1,@\Session::get('step_3')) !!}
    <div class="container">
         {!! stepCartNew(1,@\Session::get('step_3')) !!}
    </div>
   
  
        <div class="headtext top2rem">ตะกร้าสินค้า @if (count($cartitems) != 0)<span><a
                        href="{{ url('/destroyCart') }}">ลบทั้งหมด</a></span>@endif</div>
        <div class="row">
            <div class="col-lg-7 col-md-7 col-12">
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
                <form action="#" method="">
                    @if (count($cartitems) != 0)

                        @foreach ($cartitems as $KEY => $cartitem)
                            @php
                                $voucher = \App\Model\admin\VoucherModel::query()
                            ->join('main_voucher AS m','tb_voucher.relation_mainid','m.id_main')
                                 ->where('voucher_id', $cartitem->id)->first();
                                  //$voucher = DB::table('tb_voucher')->where('voucher_id','=',$cartitem->id)->first();
                            @endphp
                            <div class="row box02 top1rem">
                                <div class="col-lg-4 col-md-6 col-12" style="padding: 0px;">
                                    <img class="img-fluid d-block w-100"
                                         src="{{ URL::asset('storage/voucher/'.$voucher->img_show) }}">
                                </div>

                                <div class="col-lg-8 col-md-6 col-12">

                                    <div class="row">

                                        <div class="col-lg-10 col-md-10 col-10">
                                            <div class="">
                                                <a href="{{ url("/voucherdetail",$cartitem->id) }}" target="_blank"
                                                   style="text-decoration:none">
                                                    <div class="miniheadtext top0rem"
                                                         style="color:  #565656;"> {{ $cartitem->name }} </div>
                                                </a>

                                                <div class="travelnormaltext">สำหรับ {{  $voucher->qty_night }}</div>
                                                <div class="text-cart-main ">{{$voucher->name_main}}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-2">
                                            <div class="cancelposition">
                                                <a href="#">
                                                    <input type="hidden"/>
                                                    <i class="fas fa-times" id="del{{   $cartitem->id  }}"
                                                       data-atr="{{   $cartitem->id  }}"
                                                       style="font-size: 20px;color: #5D5D5D;"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-6">
                                            <input type="hidden" name="rowID" id="rowID{{ $cartitem->id  }}"
                                                   value="{{   $cartitem->rowId  }}"/>
                                            <div class="quantity top0rem">
                                                <input type="number" min="{{$voucher->voucher_id}}"
                                                       max="{{$voucher->qty_voucher}}" step="1" name="qty"
                                                       id="qty{{   $cartitem->id  }}" value="{{ $cartitem->qty }}">
                                                <label style="text-indent: 1em;font-size: 20px;margin-top: 3px;color: #565656">
                                                    ใบ</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-6 bot0rem">
                                            <div class="">
                                                <div class="minipricetext text-right"
                                                     style="text-decoration: line-through;color: #707070;">
                                                    ฿{{number_format($voucher->price_agent)}}
                                                </div>
                                                <div class="minipricetext text-right subtotal{{ $cartitem->id  }}"
                                                     style="color:  #FC4343;">
                                                    ฿ {{ number_format($cartitem->subtotal,0,".",",") }}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        @endforeach
                    @else
                        <h3 class="text-center">ไม่มีสินค้าในตะกร้า</h3>
                    @endif
                </form>
                <div class="row">
                    <div class="col-md-4 col-12 top0rem">
                        <a class="btn btn-md btn-block top0rem" href="{{  url('voucherbrowsing') }}" role="button"
                           style="background-color: #FBDC07;color: #000000;font-family: kanit;font-size: 18px;">เลือกดีลเพิ่ม</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-12 top1rem">
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">รายละเอียดการสั่งซื้อ</div>
                    </li>
                    @if (count($cartitems) != 0)
                        @foreach ($cartitems as $KEY => $cartitem)
                            @php
                                //  $voucher = \App\Voucher::find($cartitem->id);
                       $voucher = \App\Model\admin\VoucherModel::query()
                              ->join('main_voucher AS m','tb_voucher.relation_mainid','m.id_main')
                                   ->where('voucher_id', $cartitem->id)->first();
                            @endphp
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="jrminiheadtext" style="color:  #565656;">{{ $cartitem->name }}</div>
                                    <div class="travelnormaltext">สำหรับ {{  $voucher->qty_night }}</div>
                                    <div class="text-cart-main ">{{$voucher->name_main}}</div>
                                    <div class="travelnormaltext subqty{{ $cartitem->id  }}">
                                        จำนวน {{ $cartitem->qty }}</div>
                                </div>
                                <div class="minipricetext subtotal{{ $cartitem->id  }} " style="color:  #FC4343;">
                                    ฿ {{  number_format($cartitem->subtotal,0,".",",") }}</div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <h4 class="text-center">ไม่มีสินค้าในตะกร้า</h4>
                        </li>
                    @endif
                </ul>

                <ul class="list-group mb-3">

                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">ราคารวมทั้งสิ้น</div>
                    </li>
                    @if (count($cartitems) != 0)
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <div class="travelnormaltext">รวมทั้งหมด</div>
                            </div>
                            <div class="miniheadtext total">
                                ฿ {{ Cart::total() }}
                            </div>
                        </li>
                    @else
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <div class="travelnormaltext">ไม่มีสินค้าในตะกร้า</div>
                            </div>
                        </li>
                    @endif
                </ul>
                <a class="btn btn-md btn-block" role="button"
                   href="@if ((Session::get('id_member') == "" )) {{ url('/sign-in') }}  @elseif(count($cartitems) == 0) {{ url('/voucherbrowsing') }} @else {{ url('/paymentstarting') }} @endif"
                   style="background-color: #488BF8;color: white;font-family: kanit;font-size: 18px;">
                    ดำเนินการต่อ</a>
            </div>
        </div>
    </div>
    <div id="resultModal"></div>
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

        // function viewShow(id) {
        //         $.ajax({
        //             url: '{{ url("showdetail") }}/' + id,
        //             type: 'GET',
        //             success: function (data) {
        //                 $('#resultModal').html(data);
        //                 $("#myModal").modal('show');
        //                 $('#myModal').modal({backdrop: 'static', keyboard: false});
        //             }
        //         });
        // }
    </script>
    <script>
        $(document).ready(function () {

            var quantitiy = 0;
            $('.quantity-right-plus').click(function (e) {

                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());

                // If is not undefined

                $('#quantity').val(quantity + 1);


                // Increment

            });

            $('.quantity-left-minus').click(function (e) {
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());

                // If is not undefined

                // Increment
                if (quantity > 0) {
                    $('#quantity').val(quantity - 1);
                }
            });

        });

    </script>

    <script>
        jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
        jQuery('.quantity').each(function () {
            var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');
            step = input.attr('max');
            btnUp.click(function () {
                var oldValue = parseFloat(input.val());
                $.ajax({
                    url: "{{url('check_limit')}}/" + min + '/' + oldValue,
                    type: "get",
                }).done(function (data) {
                    if (data == 1) {
                        var newVal = oldValue;
                        spinner.find("input").val(newVal);
                        spinner.find("input").trigger("change");
                    } else {
                        var newVal = oldValue + 1;
                        spinner.find("input").val(newVal);
                        spinner.find("input").trigger("change");
                    }
                });


            });

            btnDown.click(function () {
                var oldValue = parseFloat(input.val());
                if (oldValue <= 1) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue - 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

        });
    </script>
    <script>
        $('.disabled').click(function (e) {
            e.preventDefault();
        });
    </script>
    <script>
        @foreach ($cartitems as $cartitem)

        {{ $cartitem->voucher_id }}
        $("#qty{{ $cartitem->id }}").on('change', function () {
            var newQty = $("#qty{{ $cartitem->id }}").val();
            var rowID = $("#rowID{{ $cartitem->id }}").val();
            $.ajax({
                url: '{{ url("/updatecart") }}',
                data: 'rowID=' + rowID + '&newQty=' + newQty,
                type: 'get',

            }).done(function (data) {
                //  console.log(data.subtotal);
                $(".coutnumbernavpc").html(data.count);
                $(".coutnumbernav").html(data.count);
                //  $(".coutnumbernav").html(data.count);
                $(".subtotal" + data.id).html("฿  " + data.subtotal);
                $(".subqty" + data.id).html("จำนวน  " + data.qty);
                $(".total").html("฿  " + data.total);
            });
        });
        @endforeach
    </script>

    <script>
        @foreach ($cartitems as $cartitem)

        {{ $cartitem->voucher_id }}
        $("#del{{ $cartitem->id }}").on('click', function () {
            var newDel = $("#del{{ $cartitem->id }}").attr('data-atr');
            var rowID = $("#rowID{{ $cartitem->id }}").val();
            $.ajax({
                url: '{{ url("/deletecart") }}',
                data: 'rowID=' + rowID,
                type: 'get',

            }).done(function (data) {
                if (data == 1) {
                    window.location.reload();
                }
            });
        });
        @endforeach


    </script>
@endsection