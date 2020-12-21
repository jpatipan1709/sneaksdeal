@extends('layouts.components')
@section('contentFront')
<style>
    
.btn-payment-pc{
    position:absolute;
    width:200px;
    height:40px;
    right:10px;
    top:10px
}
.btn-payment-mobile{
    display: none;
}
@media  (max-width: 767px){
     .jrminiheadtext {
    font-family: 'Kanit', sans-serif;
    font-size: 14px;
    font-weight: 600;
}
.btn-payment-pc{
    display: none;
}
.btn-payment-mobile{
    position:fixed;
    display:block;
    z-index: 9999;
    width:100%;
    height:40px;
    bottom:0
}
}
.normaltext{
    overflow-wrap:break-word;
}
</style>
    <div class="container">

       {!! stepCart(4,'') !!}
    <div class="container">
         {!! stepCartNew(4,'') !!}
    </div>        
      <div class="row">
            <div class="col-md-12">
                <div class="headtext text-center top3rem">ขอบคุณค่ะ</div>
                <div class="normaltext text-center top0rem">ทางเรายืนยัน
                    เลขที่สั่งซื้อ {{ (@$id == '' ? sprintf('%011d', $order_id):$id) }} ของคุณเรียบร้อยแล้ว
                </div>
                {{-- <img src="{{ asset('img/cart/success01.png') }}" class="d-block mx-auto top2rem bot2rem iconsize">


                <div class="normaltext text-center top0rem">โปรดคลิกปุ่มด้านล่างเพื่อกลับสู่หน้าหลัก
                </div> --}}


            </div>


        </div>


<div class="container">

    <div class="row">
        <div class="col-8">
            <div class="miniheadtext top3rem text-left">เลขที่ใบสั่งซื้อ {{str_pad($orders->id,11,"0",STR_PAD_LEFT) }}</div>
        </div>
        <div class="col-4">
            <div class="miniheadtext top3rem text-right"><a title="refresh" href="javascript:void(0)" onclick="location.reload(0)"><i class="fa fa-refresh"></i></a> รายละเอียด</div>
        </div>
    </div>
    @foreach ($order_details as $key => $order_detail)
    @php
        $text = json_decode($order_detail->data_detail);
        // print_r($text);
    @endphp
    <div class="row top1rem box" style="padding: 10px;position: relative;">
            <div class="col-6 col-md-4">
                <div class="jrminiheadtext top1rem">ราคา</div>
                <div class="normaltext top0rem">{{number_format($orders->order_total,2)}} <span style="float: right">บาท</span></div>
                <div class="jrminiheadtext top1rem">ราคาส่วนลด</div>
                <div class="normaltext top0rem">{{number_format($orders->order_discount,2)}} <span style="float: right">บาท</span></div>
                <div class="jrminiheadtext top1rem">รวมทั้งหมด</div>
                <div class="normaltext top0rem">{{number_format($orders->order_total-$orders->order_discount,2)}} <span style="float: right">บาท</span></div>
            </div>
            <div class="col-6 col-md-4">  
                <div class="jrminiheadtext top1rem">สถานะ</div>
                <div class="normaltext top0rem">{!!($status_order2)!!}</div>
                <div class="jrminiheadtext top1rem">สถานะ Voucher ที่ได้รับ</div>
                <div class="normaltext top0rem">
                   @if($orders->status_send_voucher == 'scan')
                                            <span style="color:orange">ส่งแบบ Scan แล้ว</span>
                    @elseif($orders->status_send_voucher == 'true')
                                        <span style="color:green">ส่งตัวจริงแล้ว Track no.{{$orders->tracking_no}}</span>
                    @else
                                        <span style="color:#dc3545">-ไม่ได้ทำรายการ-</span>
                     @endif
                </div>
    
                  <div class="jrminiheadtext top1rem" >อีเมล</div>
                <div class="normaltext top0rem">{{$orders->email_order}}</div>
            </div>
            
            <div class="col-6 col-md-4">  

            </div>  
             <div class="col-12 col-md-12">  
            <div class="jrminiheadtext top1rem">ที่อยู่ในการจัดส่ง</div>
             <div class="normaltext top0rem">{{$name}} {{$address}}</div>
            </div>
@if($orders->status_order == '001' || $orders->status_order == '002')          
<button type="button" onclick="frmSubmit()" class="btn btn-primary btn-payment-pc" ><i class="fa fa-money"></i> ชำระเงิน</button>
@endif            
    </div>
    <div class="row top1rem box">
        <div class="col-12 col-md-4 col-xl-4" style="padding:  0px;">
            <a data-fancybox="gallery" href="{{ url('storage/voucher/'.$text->img_show) }}">
                <img class="img-fluid d-block w-100" src="{{ url('storage/voucher/'.$text->img_show) }}"></a>
        </div>
        <div class="col-12 col-md-6 col-xl-6">
            <div class="jrminiheadtext top1rem" style="color:  #565656;">{{ $text->name_main }} </div>
            <div class="jrminiheadtext" style="color:  #565656;font-size:14px;">{{ $text->name_voucher }} </div>
            <div class="travelnormaltext top1rem">
                <strong>฿ {{ $text->priceper }}</strong>&nbsp;&nbsp;<span style="text-decoration: line-through;color: #707070;">฿
                    {{ $text->price_agent }}</span>
              &nbsp;&nbsp; &nbsp;&nbsp;  <strong>จำนวน</strong> <span>{{ $order_detail->qty }} ใบ</span>
            </div>
            <div class="border-bottom"></div>
            <div class="jrminiheadtext top1rem" style="color:  #565656;">สิ่งอำนวยความสะดวก</div>
            <div class="row">
                <div class="col-6 col-md-6">
                    @php
                    $facilitys = DB::table('tb_facilities')
                    ->whereIn('id_facilities',explode(',',$text->relation_facilityid))
                    ->limit(5)
                    ->get();
                    @endphp
                    @foreach ($facilitys as $key => $facility)
                    <div class="travelnormaltext top0rem"><img width="25" height="25" src="{{ url('storage/facilities/'.$facility->icon_facilities) }}">
                        &nbsp; {{ $facility->name_facilities }}</div>
                    @endforeach
                </div>
                <div class="col-6 col-md-6">
                    @php
                    $facilitys = DB::table('tb_facilities')
                    ->whereIn('id_facilities',explode(',',$text->relation_facilityid))
                    ->offset(5)
                    ->limit(5)
                    ->get();
                    @endphp
                    @foreach ($facilitys as $key => $facility)
                    <div class="travelnormaltext top0rem"><img width="25" height="25" src="{{ url('storage/facilities/'.$facility->icon_facilities) }}">
                        &nbsp; {{ $facility->name_facilities }}</div>
                    @endforeach
                </div>
            </div>
            <div class="border-bottom"></div>
            <div class="jrminiheadtext top1rem" style="color:  #565656;">จำนวนผู้เข้าพักได้สูงสุด</div>
            <div class="travelnormaltext ">ผู้ใหญ่ {{ $text->qty_customer }} ท่าน</div>
            <div class="border-bottom"></div>
            @if($order_detail->status_order == "000")

            <div class="jrminiheadtext top1rem" style="color:  #565656;">รหัสการใช้งาน</div>
            <div class="travelnormaltext ">
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="10%">#</th>
                                <th>Code Voucher</th>
                                <th>Code Confirm</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $order_vouchers = DB::table('order_vouchers')
                            ->where('order_detail_id','=',$text->odt_id)
                            ->get();
                            @endphp
                            @foreach ($order_vouchers as $key => $order_voucher)
                            <tr class="text-center">
                                <td>{{ ++$key }}</td>
                                <td>{{ $order_voucher->code_voucher }}</td>
                                <td>{{ $order_voucher->code_confirm }}</td>
                                <td>
                                    @if($order_voucher->stat_voucher == 'y' )
                                    <span class="text-success">{{ 'ใช้งานแล้ว' }}</span>
                                    @else
                                    <span class="text-danger">{{ 'ยังไม่ได้ใช้งาน' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border-bottom"></div>
            @endif

            <div class="jrminiheadtext top1rem" style="color:  #565656;">เงื่อนไขการใช้ Voucher</div>
            @if($text->term_voucher != null)
            <div class="travelnormaltext ">
                {!! $text->term_voucher !!}
            </div>
            <div class="row">
                <div class="col-12 col-md-12">
                    <!-- <a class="btn btn-md btn-block top1rem mb-4" href=" " role="button" style="background-color: #488BF8;color: white;font-family: kanit;">ยังไม่ได้เปิดใข้งาน</a> -->
                </div>
            </div>
            @else
            <div class="travelnormaltext ">
                ไม่มีเงื่อนไข
            </div>
            @endif
        </div>

        <div class="col-12 col-md-2 col-xl-2 d-none d-sm-block">
            <div class="distextvouchd">@php
                // echo $text->price_agent.' '.$order_detail->priceper;
                $percen = salePercen($text->price_agent,$order_detail->priceper);
                echo 100 - $percen;
                @endphp
                % OFF </div>
        </div>
    </div>
    @endforeach
</div>
@if($orders->status_order == '001' || $orders->status_order == '002')          
<button type="button" class="btn btn-primary btn-payment-mobile"  onclick="frmSubmit()"><i class="fa fa-money"></i> ชำระเงิน</button>
      @php
        
            $merchant_id = "764764000001527";		//Get MerchantID when opening account with 2C2P
        $secret_key = "853D79E418F54DCA0363B56480E4966D7CD4C40299CFD4C2820DDCCF2264A266";	//Get SecretKey from 2C2P PGW Dashboard
        // echo $order->discount_bath;
        if($orders->order_discount != null){
            $amount_total = (int)$orders->order_total - (int)$orders->order_discount;
        }else{
            $amount_total = (int)$orders->order_total;
        }
        // echo $amount_total;
        //Transaction information
        $payment_description  = json_decode($order_details[0]->data_detail)->name_voucher;
        $order_id  = str_pad($orders->id,11,"0",STR_PAD_LEFT);
        $currency = "764";
        $amount  = str_pad($amount_total,10,"0",STR_PAD_LEFT).'00';
        $country_code =  'THB';

        //Request information
        $version = "7.2";
        $payment_url = "https://t.2c2p.com/RedirectV3/payment ";
        $result_url_1 = "https://www.sneaksdeal.com/redirect";

        //Construct signature string
        $params = $version.$merchant_id.$payment_description.$order_id.$currency.$amount;
        $hash_value = hash_hmac('sha1',$params, $secret_key,false);	//Compute hash value
        @endphp

        <form id="myformpayMent" target="_alert" method="post" action="https://t.2c2p.com/RedirectV3/payment" name='paymentRequestForm'>
            @csrf
            <input type="hidden" name="version" value="7.2"/>
            <input type="hidden" name="merchant_id" value="{{ config('laravel-2c2p.merchant_id') }}"/>
            <input type="hidden" name="currency" value="764"/>
       {{--            <input type="hidden" name="payment_option" value="{{@$payment_channel}}"/>--}}
            <input type="hidden" name="hash_value" value="{{$hash_value}}"/>
            <input type="hidden" name="payment_description" value="<?php echo $payment_description; ?>"  readonly/><br/>
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"  readonly/><br/>
            <input type="hidden" name="amount" value="<?php echo $amount; ?>" readonly/><br/>
            {{--<input type="submit" name="submit" value="Confirm" />--}}
        </form>
@endif
  
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
    <input type="hidden" value="{{@json_encode($vouchers)}}" id="vouchers">
    
    {{-- @if(@$sendMail == 'true')

        <input type="hidden" value="{{url('sendmail/voucher')}}" id="urlVoucher">
        <input type="hidden" value="{{csrf_token()}}" id="token">
        <input type="hidden" value="{{@json_encode($order)}}" id="order">
        <input type="hidden" value="{{@json_encode($vouchers)}}" id="vouchers">
        <input type="hidden" value="{{@json_encode($order_vouchers)}}" id="order_vouchers">
        <input type="hidden" value="{{@json_encode($member)}}" id="member">
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
    @endif --}}



<div class="modal fade" id="exampleModalAlertPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalAlertPaymentLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalAlertPaymentLabel">แจ้งเตือน</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <h6 style="text-indent: 2em;color:red">*** กรุณาชำระเงินภายใน 24 ชั่วโมง หากเกินระยะเวลาดังกล่าว ระบบจะทำการยกเลิกออเดอร์อัตโนมัติหรือก่อนเวลา {{date('d/m/Y H:i:s',strtotime($orders->created_at)+86400)}}</h6>
      </div>
      <div class="modal-footer">
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
         function frmSubmit(){
      $('#myformpayMent').submit();
  }
        $( document ).ready(function() {
                $('#exampleModalAlertPayment').modal('show');

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