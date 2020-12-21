@extends('layouts.components')
@section('contentFront')

    <div class="container">

        {!! stepCart(4) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="headtext text-center top3rem">ขอบคุณค่ะ</div>
                <div class="normaltext text-center top0rem">ทางเรายืนยัน
                    เลขที่สั่งซื้อ {{ (@$id == '' ? sprintf('%011d', $order_id):$id) }}ของคุณเรียบร้อยแล้ว
                </div>

                <img src="{{ asset('img/cart/success01.png') }}" class="d-block mx-auto top2rem bot2rem iconsize">


                <div class="normaltext text-center top0rem">โปรดคลิกปุ่มด้านล่างเพื่อกลับสู่หน้าหลัก
                </div>


            </div>


        </div>
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <a class="btn btn-md btn-block top1rem" href="{{ url('/voucherbrowsing') }}" role="button"
                   style="background-color: #488BF8;color: white;font-family: kanit;">
                    กลับหน้าหลัก</a>


            </div>


            <div class="row top2rem">
            </div>

        </div>

    </div>
    <input type="hidden" value="{{json_encode($vouchers)}}" id="vouchers">
    @if(@$sendMail == 'true')

        <input type="hidden" value="{{url('sendmail/voucher')}}" id="urlVoucher">
        <input type="hidden" value="{{csrf_token()}}" id="token">
        <input type="hidden" value="{{json_encode($order)}}" id="order">
        <input type="hidden" value="{{json_encode($vouchers)}}" id="vouchers">
        <input type="hidden" value="{{json_encode($order_vouchers)}}" id="order_vouchers">
        <input type="hidden" value="{{json_encode($member)}}" id="member">
        <input type="hidden" value="{{$order_id}}" id="order_id">
        <script>
            var order = $("#order").val();
            var vouchers = $("#vouchers").val();
            var order_vouchers = $("#order_vouchers").val();
            var member = $("#member").val();
            var order_id = $("#order_id").val();
            // var jsonString = JSON.stringify(dataString);
            var urlVoucher = $('#urlVoucher').val();
            var token = $('#token').val();
            $.ajax({
                url: urlVoucher,
                type: "POST",
                data: {
                    _token: token,
                    order: order,
                    vouchers: vouchers,
                    order_vouchers: order_vouchers,
                    member: member,
                    order_id: order_id
                },
                success: function (res) {
                    console.log(res);
                }
            });
        </script>
    @endif





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
        $( document ).ready(function() {
            var vouchers = $("#vouchers").val()
            if(vouchers && vouchers.length > 0){
                var objVouchers = JSON.parse(vouchers)
                var arrVoucherId = []
                var price = 0
                for(var i = 0; i < objVouchers.length; i++){
                    var voucherItem = objVouchers[i]
                    arrVoucherId.push(voucherItem.voucher_id)
                    var multiplePrice = voucherItem.priceper * voucherItem.qty
                    price = voucherItem.order_total
                }
                fbq('track', 'Purchase', { content_ids: arrVoucherId, content_type: 'voucher', currency: 'THB', value: price })
            }
        });

    </script>
@endsection