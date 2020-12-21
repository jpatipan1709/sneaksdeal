
<script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>


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
<a>Ok</a>