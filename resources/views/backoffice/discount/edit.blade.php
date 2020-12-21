@extends("backoffice/layout/components")

@section('top1') Discount @endsection

@section('top2') home @endsection

@section('top3') Discount Edit @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "discount";
$active = "select_discount";
?>

@section('contents')
    @php
        $date_start= date_create($discount->date_start);
        $date_end= date_create($discount->date_end);
    @endphp
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        แก้ไข Discount
                    </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <h4>รายการส่วนลดที่ : {{ $discount->discount_id }} </h4>
                    <hr/>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ url('backoffice/discount',$discount->discount_id) }}" method="post"
                                  id="frmUpdate">
                                @csrf
                                {{ method_field('PATCH') }}


                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="type_code">รูปแบบ Code</label>
                                        <select readonly name="type_code" onchange="typeCode(this.value)"
                                                class="form-control">
                                            <option {{($discount->type_discount == 'single_code' ? 'selected':'')}} value="single_code"
                                                    selected>Single Code
                                            </option>
                                            <option {{($discount->type_discount == 'multiple_code' ? 'selected':'')}}  value="multiple_code">
                                                Multiple Code
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="discount_name">ชื่อส่วนลด</label>
                                        <input type="text" class="form-control" id="discount_name" name="discount_name"
                                               required placeholder="ชื่อส่วนลด" value="{{$discount->discount_name}}">
                                    </div>
                                    <div class="form-group col-md-4" id="divPartner">
                                        <label for="partner">Partner</label>
                                        <select name="partner_name" disabled id="partner" class="form-control">
                                            <option value="xCash" {{$discount->partner_name == 'xCash'?'selected':''}} >
                                                xCash
                                            </option>
                                            <option value="Sneaksdeal" {{$discount->partner_name == 'Sneaksdeal'?'selected':''}}>
                                                Sneaksdeal
                                            </option>

                                        </select>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="code_discount">รหัสส่วนลด</label>
                                        <input type="text" class="form-control" readonly id="code_discount"
                                               name="code_discount" placeholder="รหัสส่วนลด"
                                               value="{{ $discount->discount_code }}">
                                        @if ($errors->has('code_discount'))
                                            <span class="text-danger text-size-error" role="alert">
                                                {{ $errors->first('code_discount') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="qty">จำนวน</label>
                                        <input type="number" {{($discount->type_discount == 'single_code'?'':'readonly')}} class="form-control" id="qty" name="qty"
                                               placeholder="จำนวน" value="{{ $discount->discount_qty }}">
                                        @if ($errors->has('qty'))
                                            <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('qty') }}
                                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="discount_min">ยอดขั้นต่ำ</label>
                                        <input type="number" class="form-control" id="discount_min" name="discount_min"
                                               placeholder="ยอดขั้นต่ำ" value="{{ $discount->discount_min }}">
                                        @if ($errors->has('discount_min'))
                                            <span class="text-danger text-size-error" role="alert">
                                                        {{ $errors->first('discount_min') }}
                                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="date_start">วันที่เริ่มใช้งาน</label>
                                        <input type="date" class="form-control" id="date_start" name="date_start"
                                               placeholder="วันที่เริ่มใช้งาน"
                                               value="{{ date_format($date_start,"Y-m-d") }}">
                                        @if ($errors->has('date_start'))
                                            <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('date_start') }}
                                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_end">วันที่สิ้นสุดใช้งาน</label>
                                        <input type="date" class="form-control" id="date_end" name="date_end"
                                               placeholder="วันที่สิ้นสุดใช้งาน"
                                               value="{{ date_format($date_end,"Y-m-d") }}">
                                        @if ($errors->has('date_end'))
                                            <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('date_end') }}
                                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="discount_bath">ส่วนลด</label>
                                        <input type="number" class="form-control" id="discount_bath"
                                               name="discount_bath" placeholder="ส่วนลด"
                                               value="{{ $discount->discount_bath }}">
                                        @if ($errors->has('discount_bath'))
                                            <span class="text-danger text-size-error" role="alert">
                                                            {{ $errors->first('discount_bath') }}
                                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label for="type_code">เลือกโรงแรม</label><br>
                                    {!! inputCheckbox('<b>ทั้งหมด</b>', 'checkAll', 'checkAll', ($discount->discount_main == 0 ? "checked":''), 'all') !!}

                                </div>
                                <div class="form-row">
                                    @foreach ($main_vouchers as $main_voucher)
                                        <div class="col-md-3 div-main">
                                            {!! inputCheckbox($main_voucher->name_main, 'select_main[]', '', (in_array($main_voucher->id_main,explode(',',$discount->discount_main)) ?'checked':''), $main_voucher->id_main) !!}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <a href="{{ url('backoffice/discount') }}" class="btn btn-dark float-left">ย้อนกลับ</a>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn btn-info float-right"><i
                                                    class="far fa-edit"></i>แก้ไข
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">

                </div>
            </div>
            <div id="resultUpdate"></div>
            <!-- /.card -->
        </section>

        <!-- right col -->
    </div>
    <style>
        .label-checkbox{
            font-size: 12px!important;
            font-weight: 600!important;
        }
        .div-main div{
            margin-left: 0px!important;
        }
    </style>
@endsection
@section('script')
    <script>
        $('#checkAll').click(function () {
            if ($(this).is(":checked")) {
                $('input[name="select_main[]"]').prop("checked", true);
            } else {
                $('input[name="select_main[]"]').prop("checked", false);
            }
        });
        $('input[name="select_main[]"]').click(function () {
            var countMain = $('input[name="select_main[]"]').length;
            var checked = $('input[name="select_main[]"]:checked').length;
            if (countMain == checked) {
                $('#checkAll').prop("checked", true);
            } else {
                $('#checkAll').prop("checked", false);
            }
        });
        $(function () {
            if($('#checkAll').is(":checked")){
                $('input[name="select_main[]"]').prop("checked", true);

            }
        });
        function submit(idForm) {
            $.ajax({
                url: "{!! URL("backoffice/discount/".$discount->discount_id ) !!}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: new FormData($('#' + idForm + '')[0]), _method: "PATCH",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $('#resultUpdate').html(data);
                }
            });
        }
    </script>
    <script>

        function typeCode(val) {
            if (val == 'multiple_code') {
                $('#divPartner').show();
                $('#partner').attr('disabled', false);
                $('#code_discount').attr('readonly', true);
            } else {
                $('#divPartner').hide();
                $('#partner').attr('disabled', true);
                $('#code_discount').attr('readonly', false);

            }
        }
    </script>
@endsection


