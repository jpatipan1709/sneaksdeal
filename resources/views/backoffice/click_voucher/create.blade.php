@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3') Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "voucher";


?>

@section('contents')

    <div class="row">
        <section class="col-lg-12 ">
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <form method="get">
                    <div class="row" style="margin-left: 0px!important;margin-right:0px !important;">
                        <div class="col-md-12">
                            <h5>ผลรวมการคลิกจองแต่ละ Voucher</h5>
                        </div>
                        {!! inputSelect2('เลือก Voucher', 'voucher_', '', '', 'md-6', 'required', $option) !!}
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-outline-success" style="margin-top:28px"><i
                                        class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>


        </section>
    </div>
    {{--<script>--}}
    {{--alertify.error('sss');--}}
    {{--</script>--}}
    <div id="resultCreate"></div>

@endsection

@section('script')

    <script>
        $(document).ready(function () {
            $('#sale').blur(function () {
                var price_agent = $('#price_agent').val();
                var sale = $('#sale').val();
                var total = parseFloat(price_agent) - parseFloat(sale);
                $('#price').val(total);
            });


            $('#switch').change(function () {
                if ($('#switch').is(":checked")) {
                    $('#qty_sale').removeAttr("required");
                    $('#detailSwitch').hide();
                } else {
                    $('#qty_sale').attr("required");
                    $('#detailSwitch').show();

                }
            });

            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY HH:mm'

            })


        });

        // $("form").on("submit", function () {
        //     for ( instance in CKEDITOR.instances ) { CKEDITOR.instances[instance].updateElement();}
        //     $.ajax({
        //         url: "../voucher",
        //         type: "POST",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: new FormData($('#frmCreate')[0]),
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         success: function (data) {
        //             $('#resultCreate').html(data);
        //             // if(data.errors) {
        //             //     jQuery.each(data.errors, function (key, value) {
        //             //         $('#validatorHead').show();
        //             //         $('#validator').append("<li >" + value + "</li>")
        //             //     });
        //             // }else{
        //             //     $('#resultCreate').html(data);
        //             //     $('#validatorHead').hide();
        //             //
        //             // }
        //         }
        //     });
        //     return false;

        // });


    </script>

@endsection
