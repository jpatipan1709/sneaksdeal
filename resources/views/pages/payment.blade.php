@extends('layouts.components')
@section('contentFront')
    <div class="container">

        @php
        
            $merchant_id = "764764000001527";		//Get MerchantID when opening account with 2C2P
        $secret_key = "853D79E418F54DCA0363B56480E4966D7CD4C40299CFD4C2820DDCCF2264A266";	//Get SecretKey from 2C2P PGW Dashboard
        // echo $order->discount_bath;
        if($order->discount_bath != null){
            $amount_total = (int)$order->sum_total - (int)$order->discount_bath;
        }else{
            $amount_total = (int)$order->sum_total;
        }
        // echo $amount_total;
        //Transaction information
        $payment_description  = $order->name_voucher;
        $order_id  = str_pad($order->id,11,"0",STR_PAD_LEFT);
        $currency = "764";
        $amount  = str_pad($amount_total,10,"0",STR_PAD_LEFT).'00';
        $country_code =  'THB';

        //Request information
        $version = "7.2";
        $payment_url = "https://t.2c2p.com/RedirectV3/payment ";
        $result_url_1 = "https://www.sneaksdeal.com/redirect";

        //Construct signature string
        // $params = $version.$merchant_id.$payment_description.$order_id.$currency.$amount.$result_url_1;
        $params = $version.$merchant_id.$payment_description.$order_id.$currency.$amount.$payment_channel;
        $hash_value = hash_hmac('sha1',$params, $secret_key,false);	//Compute hash value
        @endphp

        <form id="myform" target="_alert" method="post" action="https://t.2c2p.com/RedirectV3/payment" name='paymentRequestForm'>
            @csrf
            <input type="hidden" name="version" value="7.2"/>
            <input type="hidden" name="merchant_id" value="{{ config('laravel-2c2p.merchant_id') }}"/>
            <input type="hidden" name="currency" value="764"/>
           <input type="hidden" name="payment_option" value="{{@$payment_channel}}"/>
            <input type="hidden" name="hash_value" value="<?php echo hash_hmac('sha1',$params, $secret_key,false); ?>"/>
            <input type="hidden" name="payment_description" value="<?php echo $payment_description; ?>"  readonly/><br/>
            <input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id; ?>"  readonly/><br/>
            <input type="hidden" name="amount" value="<?php echo $amount; ?>" readonly/><br/>
            {{-- <input type="submit" name="submit" value="Confirm" /> --}}
        </form>
    </div>
@endsection
@section('scriptFront')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-147734154-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-147734154-1');
</script>
<script>
    $(function () {
        var order_id = $('#order_id').val();
     $("#myform").submit();
         setInterval(function(){ window.location="/cart_success/"+order_id; }, 2000);

    });

</script>
{{-- <script type="text/javascript" src="{{ config('laravel-2c2p.secure_pay_script') }}"></script>
<script type="text/javascript">
    My2c2p.onSubmitForm("2c2p-payment-form", function(errCode,errDesc){
        if(errCode!=0){ 
            alert(errDesc);
        }
    });
</script> --}}

@endsection