@extends('layouts.components')
@section('contentFront')
    <div class="container">
       {!! stepCart(3,'') !!}
    <div class="container">
         {!! stepCartNew(3,'') !!}
    </div>
        <div class="headtext top2rem">ช่องทางการชำระเงิน</div>
        <form class="needs-validation" novalidate="" action="{{ url('addorder') }}"
              onSubmit="return checkPaymentChanel()" method="post" id="form_addorder">
            @csrf
            <div class="row">

                <div class="col-lg-7 col-md-7 top1rem">
                    <div class="list-group-item">
                        <div class="top1rem"></div>
                        <div class="radio radiocheck01 padq">
                            {{--                        <label class="qusetiontextmini"> ชำระผ่านบัตรเครดิต</label>--}}
                            <div class="radio">
                                <label class="qusetiontextmini"><input type="radio" name="payment_channel" value="CC">
                                    ชำระผ่านบัตรเครดิต</label>
                            </div>
                        </div>
                        <div style="padding-left:5%;">
                            <div class="row">
                                <div class="col-lg-8 col-12">
                                    <div class="row">
                                        <div class="col-3 col-lg-3 mt-2">
                                            <img class="img-responsive" src="img/payment/mastercard.png">

                                        </div>
                                        <div class="col-3 col-lg-3 mt-2">
                                            <img class="img-responsive" src=" img/payment/visa.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">

                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="radio radiocheck01 padq">
                            {{--                        <label class="qusetiontextmini"> ชำระเงินผ่านเคาน์เตอร์</label>--}}
                            <div class="radio">
                                <label class="qusetiontextmini"><input type="radio" name="payment_channel" value="OTC">
                                    ชำระเงินผ่านเคาน์เตอร์</label>
                            </div>
                        </div>

                        <div style="padding-left:5%;">
                            <div class="row">
                                <div class="col-lg-8 col-12">
                                    <div class="row">
                                        <div class="col-3 col-lg-3 mt-2">
                                            <img class="img-responsive" src="img/payment/familymart.png">
                                        </div>
                                        <div class="col-3 col-lg-3 mt-2">
                                            <img class="img-responsive" src="img/payment/tescolotus.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/truemoneyexpress.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/mpay.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/bigc.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="radio radiocheck01 padq">
                            {{--                        <label class="qusetiontextmini"> ชำระเงินผ่านธนาคาร--}}
                            {{--                            <br>--}}
                            {{--                            เคาน์เตอร์ / ตู้ ATM / อินเทอร์เน็ต แบงค์กิ้ง--}}
                            {{--                        </label>--}}
                            <div class="radio">
                                <label class="qusetiontextmini"><input type="radio" name="payment_channel" value="123">
                                    ชำระเงินผ่านธนาคาร
                                    <br>
                                    เคาน์เตอร์ / ตู้ ATM / อินเทอร์เน็ต แบงค์กิ้ง</label>
                            </div>
                        </div>

                        <div style="padding-left:5%;">
                            <div class="row">
                                <div class="col-lg-8 col-12">
                                    <div class="row">
                                        <div class="col-3 col-lg-3 mt-2">
                                            <img class="img-responsive" src="img/payment/kasikorn.png">
                                        </div>
                                        <div class="col-3 col-lg-3 mt-2">
                                            <img class="img-responsive" src="img/payment/smb.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/krungthai.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/tmb.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/uob.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/ayudhya.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/bangkokbank.png">
                                        </div>
                                        <div class="col-lg-3 col-3 mt-2">
                                            <img class="img-responsive" src="img/payment/thanachart.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    $voucher = \App\Model\admin\VoucherModel::query()
                                            ->join('main_voucher AS m','tb_voucher.relation_mainid','m.id_main')
                                                 ->where('voucher_id', $cartitem->id)->first();
                                @endphp
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <div class="jrminiheadtext" style="color:  #565656;">{{ $cartitem->name }}</div>
                                        <div class="travelnormaltext">สำหรับ {{ $voucher->qty_night }}</div>
                                        <div class="text-cart-main ">{{$voucher->name_main}}</div>
                                    </div>
                                    <div>
                                        <div class="minipricetext text-right"
                                             style="text-decoration: line-through;color: #707070;">
                                            ฿{{number_format($voucher->price_agent)}}
                                        </div>
                                        <div class="minipricetext" style="color:  #FC4343;">
                                            ฿{{number_format($cartitem->subtotal,0,".",",") }}
                                        </div>
                                    </div>
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
                                    <div class="travelnormaltext">รวม</div>
                                </div>
                                <div class="miniheadtext">
                                    ฿
                                    @if ($total_after < 0) {{ '0' }} @else {{ $total_after }} @endif </div>
                            </li>
                            <li
                                    class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">ส่วนลด</div>
                                </div>
                                <div class="miniheadtext">
                                    ฿
                                    @if ($total_discount < 0) {{ '0' }} @else {{ $total_discount }} @endif </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">รวมทั้งหมด</div>
                                </div>
                                <div class="miniheadtext">
                                    ฿
                                    @if ($total_end < 0) {{ '0' }} @else {{ $total_end }} @endif </div>
                            </li>
                        @else
                            <li
                                    class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">ไม่มีสินค้าในตะกร้า</div>
                                </div>
                            </li>
                        @endif
                    </ul>
                  <div class="row">
                    <div class="col-6">
                        <a class="btn btn-md btn-block" href="/paymentstarting"
                                style="background-color: #ffffff;color: gray;border:2px solid gray;font-family: kanit;font-size: 18px;">
                            ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" id="btn-next-payment" class="btn btn-md btn-block add_order" role="button"
                                style="background-color: #488BF8;color: white;font-family: kanit;font-size: 18px;">
                            ดำเนินการต่อ
                        </button>
                    </div>
                   </div>
                </div>
            </div>
        </form>
    </div>

    {{--</div>--}}


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
    <script type="text/javascript">
        function checkPaymentChanel() {
            var i = 0;
            $('input[name="payment_channel"]').each(function () {
                if ($(this).is(":checked")) {
                    i = 1;
                }
                if (i == 1) {
                    return true;
                } else {
                    return false;
                }
            });
        }


        $(document).ready(function () {
            var radiocheck = $('.radiocheck input:checked').attr('rel');
            $('.radiocheck input').click(function () {
                var radiocheck = $('.radiocheck input:checked').attr('rel');
                $('.wrap_radioinsure').slideUp();
                $('.' + radiocheck).slideDown();
            });


            $('#btn-next-payment').click(function () {
        // window.open('/cart_success_status');
            });
        });

        $(document).ready(function () {
            var radiocheck01 = $('.radiocheck01 input:checked').attr('rel');
            $('.radiocheck01 input').click(function () {
                var radiocheck01 = $('.radiocheck01 input:checked').attr('rel');
                $('.wrap_radioinsure01').slideUp();
                $('.' + radiocheck01).slideDown();
            });
        });

        $(document).ready(function () {
            var radiocheck02 = $('.radiocheck02 input:checked').attr('rel');
            $('.radiocheck02 input').click(function () {
                var radiocheck02 = $('.radiocheck02 input:checked').attr('rel');
                $('.wrap_radioinsure02').slideUp();
                $('.' + radiocheck02).slideDown();
            });
        });

        $(document).ready(function () {
            var radiocheck03 = $('.radiocheck03 input:checked').attr('rel');
            $('.radiocheck03 input').click(function () {
                var radiocheck03 = $('.radiocheck03 input:checked').attr('rel');
                $('.wrap_radioinsure03').slideUp();
                $('.' + radiocheck03).slideDown();
            });
        });

        $(document).ready(function () {
            var radiocheck04 = $('.radiocheck04 input:checked').attr('rel');
            $('.radiocheck04 input').click(function () {
                var radiocheck04 = $('.radiocheck04 input:checked').attr('rel');
                $('.wrap_radioinsure04').slideUp();
                $('.' + radiocheck04).slideDown();
            });
        });
    </script>

@endsection