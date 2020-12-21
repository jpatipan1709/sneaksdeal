@extends("backoffice/layout/components")

@section('top1') Main Voucher @endsection

@section('top2') home @endsection

@section('top3') Main Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "main_voucher";


?>

@section('contents')

    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        เพิ่มข้อมูล
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{ url('backoffice/main_voucher') }}" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" onsubmit="return checkType()" method="post"
                      action="{{ url('backoffice/main_voucher') }} " enctype="multipart/form-data">
                    @csrf
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <label>ข้อมูล Main Voucher</label>
                            </div>
                            @php $textSpan = ' <span style="color:red"> (ถ้าไม่มีเว้นว่าง)</span>';
                            @endphp

                            <div class="col-lg-6">
                                {!! uploadMultipleImage(url('backoffice/dist/img/img-default.jpg'),'Album Main','Size<br>1,620
                                x 1080 ','lg-6 ','400px','200px') !!}


                                {!! inputText('ชื่อ Main', 'name_main', 'id-input', 'Enter ...', 'lg-12', 'required','') !!}
                                {!! inputText('ราคาประมาณ', 'price_main', 'id-input', 'Enter ...', 'lg-12', 'required','')
                                !!}
                                {!! inputText('link'.$textSpan, 'link', 'id-input', 'Enter ...', 'lg-12', '','') !!}


                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label>ประเภทดีล <span style="color: red;display: none"
                                                           id="txtDeal">*กรุณาเลือกประเภท</span></label>
                                    @if($errors->has('type_voucher'))
                                        <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('type_voucher') }}
                                                </span>
                                    @endif
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        @foreach ($typeVoucher as $value)
                                            {!! inputCheckbox( $value->name_type.'('.$value->code_type.')', 'type_voucher[]','','data-show="'.$value->type_show.'"', $value->code_type) !!}
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label>แสดง VDO ก่อน</label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="stat_show" value="y" id="switch">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                {!! inputText('เบอร์โทร', 'tel_main', 'id-input', 'Enter ...', 'lg-12', '','') !!}
                                {!! inputTextArea('ที่อยู่', 'address_main', 'id-input', 'Enter ...', 'lg-12', '','') !!}
                                {!! inputText('เวลาทำการ', 'time_main', 'id-input', 'Enter ...', 'lg-12', 'required','') !!}
                                {!! inputTextArea('link VDO', 'link_vdo', 'id-input', '', 'lg-12', '','') !!}

                            </div>
                            <div class="col-lg-12">
                                {!! inputTextArea('รายละเอียด'.$textSpan, 'detail_main', 'id-input', 'ckeditor', 'lg-12',
                                '','') !!}

                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-info float-right"><i class="fa fa-plus"></i> Add
                            item
                        </button>
                    </div>
                </form>
            </div>


            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>

    <div id="resultCreate"></div>

@endsection

@section('script')
    <script>
        function checkType() {
            var i = 0;
            $('input[name="type_voucher[]"]').each(function () {
                if ($(this).is(":checked")) {
                    i = 1;
                }
            });
            if (i == 0) {
                $('#txtDeal').show();
                return false;
            } else {
                $('#txtDeal').hide();
                return true;

            }
        }

        $('input[name="type_voucher[]"]').click(function () {
            $('#txtDeal').hide();
            var show = $(this).data("show");
            if (show == 'single') {
                $('input[name="type_voucher[]"]').prop("checked",false);
                $(this).prop("checked",true);
            }


        });
        // CKEDITOR.replace('ckeditor');
    </script>

    <script>
        // $(function () {
        //     $("form").on("submit", function () {
        //         for ( instance in CKEDITOR.instances ) {
        //             CKEDITOR.instances[instance].updateElement();
        //         }
        //         $.ajax({
        //             url: "{{ url('backoffice/main_voucher') }}",
        //             type: "POST",
        //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //             data: new FormData($('#frmCreate')[0]),
        //             contentType: false,
        //             cache: false,
        //             processData: false,
        //             success: function (data) {
        //                 $('#resultCreate').html(data);
        //             }
        //         });
        //         return false;

        //     });
        // });


    </script>

@endsection
