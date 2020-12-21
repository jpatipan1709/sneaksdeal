@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3') Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "voucher";


?>
@php $optionBLog = '<option value="">--กรุณาเลือกข้อมูล--</option>';
foreach ($data as $value){
  $optionBLog .=  '<option '.($main == $value->id_main ?'selected':'').' value="'.$value->id_main.'">--'.$value->name_main.'--</option>';
}
@endphp
@section('contents')

    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        กรุณากรอกรายละเอียด ข้อมูล Voucher
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                    class="fa fa-arrow-left"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{ url('backoffice/voucher') }}"
                      enctype="multipart/form-data">
                    @csrf
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12" id="validatorHead" style="display: none;">
                                <div class="card bg-warning-gradient">
                                    <div class="card-header">
                                        <h3 class="card-title headValidate">Validator</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body" id="validator">
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-lg-12">
                                {!!   inputSelect2('กรุณาเลือกข้อมูล Main Voucher', 'main_voucher', 'main_voucher', '', 'lg-12', 'required', $optionBLog)!!}
                            </div>
                            <div class="col-lg-4">
                                <label>ประเภทการขาย (ON = ซื้อขายตรงยังผู้ประกอบการ , OFF = ซื้อขายผ่าน
                                    SneaksDeal)</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="typeVoucher" value="out" id="switchType">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-lg-4 typeSwitch"><br>
                                <div class="form-group ">
                                    <input type="text" name="link_voucher_contact" id="link_voucher_contact"
                                           placeholder="link.."
                                           class="form-control" value="{{ old('link_voucher_contact') }}">
                                </div>
                            </div>
                            <div class="col-lg-4 typeSwitch"><br>
                                <div class="form-group ">
                                    <input type="text" name=tel_voucher_contact" id="tel_voucher_contact"
                                           placeholder="tel.."
                                           class="form-control" value="{{ old('tel_voucher_contact') }}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <hr>
                            </div>
                            <div class="col-lg-2"><br>
                                <label>คำนวนยอดขาย Auto</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="sale_auto" value="1" id="switch">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-lg-4"><br>
                                <div id="detailSwitch" class="form-group">
                                    <input type="number" name="qty_sale" id="qty_sale" placeholder="QTY.."
                                           class="form-control" value="{{ old('qty_sale') }}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <hr>
                            </div>


                            <div class="col-lg-6">

                                {!! uploadMultipleImage(url('backoffice/dist/img/img-default.jpg'),'Album Voucher','Size<br>1,620 x 1080 ','lg-12 ','100%','200px') !!}
                                <div class="col-lg-12">
                                    <label>สิ่งอำนวยความสะดวก</label>
                                    @if ($errors->has('facilities'))
                                        <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('facilities') }}
                                                </span>
                                    @endif
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        @foreach ($facilities as $valueFac)
                                            {!! inputCheckbox( $valueFac->name_facilities, 'facilities[]', '', '', $valueFac->id_facilities) !!}
                                        @endforeach

                                    </div>
                                </div>
                                <br>
                                {!! inputText('ชื่อ Voucher', 'name_voucher', 'id-input', 'Enter ...', 'lg-12', 'required',old('name_voucher')) !!}
                                {!! inputText('จำนวนผู้เข้าพักสูงสุด (ไม่มีเว้นว่าง)', 'qty_rest', 'id-input', 'Enter ...', 'lg-12', '',old('qty_rest')) !!}
                                {!! inputText('จำนวนคืน (ไม่มีเว้นว่าง)', 'qty_night', 'id-input', 'Enter ...', 'lg-12', '',old('qty_night')) !!}
                                {!! inputNumber('จำนวน Voucher', 'qty_voucher', 'qty_voucher', 'Enter ...', 'lg-12 divQtyVoucher', 'required',old('qty_voucher')) !!}

                            </div>
                            <div class="col-lg-6">
                                {!! uploadSingleImage(url('backoffice/dist/img/img-default.jpg'),'Image index','Size<br>1,620 x 1080 ','lg-12 ','100%','200px') !!}
                                {!! inputNumber('ราคาจริง (บาท)', 'price_agent', 'price_agent', 'Enter ...', 'lg-12', 'required',old('price_agent')) !!}
                                {!! inputNumber('ส่วนลด (บาท)', 'sale', 'sale', 'Enter ...', 'lg-12', 'required',old('sale')) !!}
                                {!! inputNumber('ราคาขาย (บาท)', 'price', 'price', 'Enter ...', 'lg-12', 'required readonly',old('price')) !!}
                                {!! inputTextArea('title', 'title_voucher', 'id-input', 'ckeditor', 'lg-12', '',old('title_voucher')) !!}
                                {!! inputText('ชื่อหัวข้อพิเศษ (ไม่มีเว้นว่าง)', 'name_extra', 'id-input', 'Enter ...', 'lg-12',
                                                          '','') !!}
                                {!! inputTextArea('รายละเอียดหัวข้อพิเศษ (ไม่มีเว้นว่าง)', 'detail_extra', 'id-input', 'ckeditor', 'lg-12',
                                                                  '','') !!}

                            </div>
                            <div class="col-lg-12">
                                <label>เลือกรูปแบบนับถอยหลัง <br>(ON =  สำหรับข้อมูลประกาศเวลาก่อนการขายจริง, OFF =
                                    สำหรับข้อมูลเวลาขายจริง
                                    SneaksDeal)</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="status_countdown" value="post" id="status_countdown">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-lg-12">
                                <div class="row" style="margin: 0!important;">
                                    {!! inputTimeRank('เวลาเปิดขาย:', 'time_open', 'reservationtime', 'time rank..', 'lg-6', 'required',old('reservationtime')) !!}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                {!! inputTextArea('เงื่อนไขการใช้ Voucher', 'term_voucher', 'id-input', 'ckeditor', 'lg-12', 'required',old('term_voucher')) !!}
                            </div>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-info float-right"><i class="fa fa-plus"></i>
                            Add
                            item
                        </button>
                    </div>
                </form>
            </div>


            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>
    {{--<script>--}}
    {{--alertify.error('sss');--}}
    {{--</script>--}}
    <div id="resultCreate"></div>

@endsection

@section('script')

    <script>

        $(document).ready(function () {
            // $('.typeSwitch').hide();


            $('#sale').blur(function () {
                var price_agent = $('#price_agent').val();
                var sale = $('#sale').val();
                var total = parseFloat(price_agent) - parseFloat(sale);
                $('#price').val(total);
            });


            $('#switch').change(function () {
                if ($('#switch').is(":checked")) {
                    // $('#qty_sale').removeAttr("required");
                    // $('#detailSwitch').hide();
                } else {
                    // $('#qty_sale').attr("required");
                    // $('#detailSwitch').show();

                }
            });

            $('#switchType').change(function () {
                if ($('#switchType').is(":checked")) {
                    $('#qty_voucher').removeAttr("required");
                    $('.divQtyVoucher').hide();
                    // $('#tel_voucher_contact').attr("required");
                    // $('#link_voucher_contact').attr("required");
                    // $('.typeSwitch').show();
                } else {
                    $('#qty_voucher').attr("required", true);
                    $('.divQtyVoucher').show();
                    // $('#tel_voucher_contact').val("");
                    // $('#link_voucher_contact').val("");
                    // $('#tel_voucher_contact').removeAttr("required");
                    // $('#link_voucher_contact').removeAttr("required");
                    // $('.typeSwitch').hide();


                }
            });

            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY HH:mm'

            })
            // $('#date_end_post').datepicker({
            //     format: 'dd-mm-yyyy'
            //
            // })

            $('#link_voucher_contact').keyup(function () {
                // if ($(this).val() != '') {
                //     $('#tel_voucher_contact').removeAttr("required");
                //     $('#tel_voucher_contact').attr("readonly", true);
                //     $('#tel_voucher_contact').val("");
                // } else {
                //     $('#tel_voucher_contact').attr("required");
                //     $('#tel_voucher_contact').attr("readonly", false);
                //     $('#tel_voucher_contact').val("");
                // }
            });
            $('#tel_voucher_contact').keyup(function () {
                // if ($(this).val() != '') {
                //     $('#link_voucher_contact').removeAttr("required");
                //     $('#link_voucher_contact').attr("readonly", true);
                //     $('#link_voucher_contact').val("");
                // } else {
                //     $('#link_voucher_contact').attr("required");
                //     $('#link_voucher_contact').attr("readonly", false);
                //     $('#link_voucher_contact').val("");
                // }
            });
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
