@extends('layouts.components')
@section('contentFront')
    <div class="container">

       {!! stepCart(2,@\Session::get('step_3')) !!}
    <div class="container">
         {!! stepCartNew(2,@\Session::get('step_3')) !!}
    </div>

        <div class="headtext top2rem">ข้อมูลลูกค้า</div>
        <form class="needs-validation" novalidate="" action="{{ route('addorder') }}" method="post">
            @csrf
            <div class="row">

                <div class="col-lg-7 col-md-7 top1rem">
                    <div class="list-group-item">

                        <div class="row top1rem">
                            <div class="col-md-8 mb-3">
                                <div class="travelnormaltext">อีเมลสำหรับรับข้อมูล Voucher</div>
                                <input type="email" class="form-control top0rem" id="email_voucher" name="email_voucher"
                                       placeholder="E-mail" required
                                       style="font-family:  kanit;"
                                       value="@if(old('email_voucher') != "") {{ old('email_voucher') }} @else {{ $members->email_send_order }}   @endif">
                                @if ($errors->has('email_voucher'))
                                    <span class="text-danger" style="font-size:12px;">
                                    {{ $errors->first('email_voucher') }}
                                      </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">ชื่อ</div>
                                <input type="text" class="form-control top0rem" id="firstName" name="firstName"
                                       placeholder="ชื่อ" required
                                       style="font-family:  kanit;"
                                       value="@if(old('firstName') != "") {{ old('firstName') }} @else {{ $members->name_member }} @endif">
                                @if ($errors->has('firstName'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('firstName') }}
                            </span>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">นามสกุล</div>
                                <input type="text" class="form-control top0rem" id="lastName" name="lastName"
                                       placeholder="นามสกุล" required
                                       style="font-family:  kanit;"
                                       value="@if(old('lastName') != ""){{ old('lastName') }}@else{{ $members->lastname_member }}@endif">
                                @if ($errors->has('lastName'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('lastName') }}
                            </span>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">โทรศัพท์</div>
                                <input type="number" class="form-control top0rem" id="tel" name="tel"
                                       placeholder="กรุณากรอกเบอร์โทรศัพท์" required
                                       style="font-family:  kanit;"
                                       value="@if(old('tel') != ""){{ old('tel') }}@else{{ $members->tel_member }}@endif">
                                @if ($errors->has('tel'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('tel') }}
                            </span>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">อีเมล</div>
                                <input type="email" class="form-control top0rem" id="email" name="email"
                                       placeholder="กรุณาระบุอีเมลของคุณ" required
                                       style="font-family:  kanit;"
                                       value="@if(old('email') != ""){{ old('email') }}@else{{ $members->email }}@endif">
                                @if ($errors->has('email'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('email') }}
                            </span>
                                @endif
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="travelnormaltext">ที่อยู่</div>
                                <input type="text" class="form-control top0rem" id="address" name="address"
                                       placeholder="กรุณาระบุที่อยู่ของคุณ" required
                                       style="font-family:  kanit;"
                                       value="@if(old('address') != ""){{ old('address') }}@else{{ $members->address_member }}@endif">
                                @if ($errors->has('address'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('address') }}
                            </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">แขวง/ตำบล</div>
                                <div class="top0rem"></div>
                                <input type="text" class="form-control" name="districts" id="districts"
                                       placeholder="กรุณาระบุแขวง/ตำบล" required
                                       value="@if(old('districts') != ""){{ old('districts') }}@else{{ $members->districts_id }}@endif"/>
                                {{-- <select class="simple-select2 w-100 districts" name="districts">
                                    <optgroup label="แขวง/ตำบล">
                                        <option value="0">เลือกแขวง/ตำบลของท่าน</option>
                                        @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" @if(($members->districts_id)==($district->id))
                                            {{ 'selected' }} @endif >{{ $district->name_th }}</option>
                                        @endforeach
                                    </optgroup>
                                </select> --}}
                                @if ($errors->has('districts'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('districts') }}
                            </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3 ui-widget">
                                <div class="travelnormaltext">เขต/อำเภอ</div>
                                <div class="top0rem"></div>
                                <input type="text" class="form-control ampuhers" name="ampuhers" id="ampuhers"
                                       placeholder="กรุณาระบุเขต/อำเภอ" required
                                       value="@if(old('ampuhers') != ""){{ old('ampuhers') }}@else{{ $members->amphures_id }}@endif"/>
                                {{-- <select class="simple-select2 w-100 ampuhers" name="ampuhers">
                                    <optgroup label="เขต/อำเภอ">
                                        <option value="0">เลือกเขต/อำเภอของท่าน</option>
                                        @foreach ($amphures as $amphure)
                                        <option value="{{ $amphure->id }}" @if(($members->amphures_id)==($amphure->id)) {{
                                            'selected' }} @endif >{{ $amphure->name_th }}</option>
                                        @endforeach
                                    </optgroup>
                                </select> --}}
                                @if ($errors->has('ampuhers'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('ampuhers') }}
                            </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">จังหวัด</div>
                                <div class="top0rem"></div>
                                <select class="simple-select2 w-100 province" name="province" required>
                                    <optgroup label="จังหวัด">
                                        <option value="0">เลือกจังหวัดของท่าน</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}" @if(old('province') == $province->id) {{ 'selected' }} @endif @if($members->provinces_id == $province->id) {{ 'selected' }} @endif >{{ $province->name_th }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @if ($errors->has('province'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('province') }}
                            </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="travelnormaltext">รหัสไปรษณีย์</div>
                                <input type="text" class="form-control top0rem postcode" id="postcode" name="postcode"
                                       required
                                       placeholder="กรุณาระบุรหัสไปรษณีย์" maxlength="5" style="font-family:  kanit;"
                                       value="@if(old('postcode') != ""){{ old('postcode') }}@else{{ $members->zip_code }}@endif">
                                @if ($errors->has('postcode'))
                                    <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('postcode') }}
                            </span>
                                @endif
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
                                    //$voucher = \App\Voucher::find($cartitem->id);
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
                    @php
                        $total = (int)str_replace(',', '', Cart::total());
                        $discout_promo_bath  =  (@Session::get('discout_promo_bath') !=''? @Session::get('discout_promo_bath') :0);
                       $grandTotal = $total - $discout_promo_bath;

                    @endphp
                    <ul class="list-group mb-3">

                        <li class="list-group-item d-flex justify-content-between lh-condensed"
                            style="background-color: #e0e6f5;">
                            <div class="miniheadtext" style="color:  #3D3D3D;">ราคารวมทั้งสิ้น</div>
                        </li>
                        @if (count($cartitems) != 0)
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">ราคา</div>
                                </div>
                                <div class="miniheadtext total_bath">
                                    ฿ {{ Cart::total() }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">ส่วนลด</div>
                                </div>
                                <div class="miniheadtext discount_check">
                                    ฿ {{ number_format($discout_promo_bath)}}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">รวมทั้งหมด</div>
                                </div>
                                <div class="miniheadtext total_check">
                                    ฿ {{number_format($grandTotal) }}
                                </div>
                            </li>
                            <div class="card p-2">
                                <div class="input-group">
                                    <input type="text" class="form-control " name="code_promo" id="code_promo"
                                           placeholder="ส่วนลด/รหัสโปรโมชั่น"
                                           style="font-family:  kanit;">

                                    <div class="input-group-append">
                                        <a class="btn btn-secondary" style="font-family:  kanit;color:white;"
                                           id="check_code">ยืนยัน</a>
                                    </div>
                                </div>
                                <div id="show_check" class=""></div>
                            </div>
                        @else
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <div class="travelnormaltext">ไม่มีสินค้าในตะกร้า</div>
                                </div>
                            </li>
                        @endif
                    </ul>
                    <input type="hidden" name="total_end" value="{{ number_format($grandTotal) }}"/>
                    <input type="hidden" name="total_after" value="{{ Cart::total() }}"/>
                    <input type="hidden" name="total_discount" value="{{number_format($discout_promo_bath)}}"/>
                    <div class="row">
                    <div class="col-6">
                     <a class="btn btn-md btn-block" href="/cart"
                            style="background-color: #ffffff;color: gray;border:2px solid gray;font-family: kanit;font-size: 18px;">
                        ย้อนกลับ
                    </a>
                </div>
                  <div class="col-6">
                    <button class="btn btn-md btn-block" role="button" type="submit"
                            style="background-color: #488BF8;color: white;font-family: kanit;font-size: 18px;">
                        ดำเนินการต่อ
                    </button>
                </div>
                </div>
                </div>
            </div>
        </form>
    </div>




@endsection

@section('scriptFront')

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

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
        $(document).ready(function () {
            $('.simple-select2').select2({
                theme: 'bootstrap4',
                placeholder: "Select an option",
                allowClear: true
            });

            $('.simple-select2-sm').select2({
                theme: 'bootstrap4',
                containerCssClass: ':all:',
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
    <script>
        $(".province").change(function () {
            var select = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: 'post',
                url: '{{ route("fecth.amphur") }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'select': select,
                },
                success: function (data) {
                    $(".ampuhers").html(data);
                }
            });

        });

        // $(".ampuhers").change(function(){
        //     var select = $(this).val();
        //     var _token = $('input[name="_token"]').val();
        //     $.ajax({
        //     type: 'post',
        //     url: '{{ route("fecth.districts") }}',
        //     data: {
        //         '_token': $('input[name=_token]').val(),
        //         'select': select,
        //     },
        //         success: function(data) {
        //             $(".districts").html(data);
        //         }
        //     });

        // });

        // $(".districts").change(function(){
        //     var select = $(this).val();
        //     var _token = $('input[name="_token"]').val();
        //     $.ajax({
        //     type: 'post',
        //     url: '{{ route("fecth.postcode") }}',
        //     data: {
        //         '_token': $('input[name=_token]').val(),
        //         'select': select,
        //     },
        //         success: function(data) {
        //             $(".postcode").val(data);
        //         }
        //     });

        // });
        function messageAlert(text, status) {
            Swal.fire({
                position: 'top-end',
                icon: '' + status + '',
                title: '' + text + '',
                showConfirmButton: false,
                timer: 1500
            })
        }

        $("#check_code").click(function () {
            var code = $("#code_promo").val();
            var token = $('input[name=_token]').val();
            if (code != '') {
                $.ajax({
                    type: 'post',
                    url: '{{ route("checkcode") }}',
                    data: {
                        '_token': token,
                        'code': code,
                    },
                    success: function (data) {
                        console.log(data);
                        messageAlert(data.message, data.status);
                        if (data.status == 'success') {
                            $("input[name=total_after]").val(data.total);
                            $("input[name=total_discount]").val(data.discount);
                            $("input[name=total_end]").val(data.grandTotal);
                            $('.discount_check').html(" ฿ " + data.discount);
                            $('.total_check').html(" ฿ " + data.grandTotal);
                        }

                    }
                })
            } else {
                messageAlert('กรุณากรอก Code ส่วนลด', 'info');
            }
            // Swal.fire({
            //     position: 'top-end',
            //     icon: 'success',
            //     title: 'Your work has been saved',
            //     showConfirmButton: false,
            //     timer: 1500
            // })
            // console.log("data : "+data);
            //  console.log("condition : "+data.condition);
            //  console.log("discout: "+data.discout_promo_bath);
            // if (data.condition == 2) {
            //     $("#show_check").html('ทำการใช้ส่วนลดโปรโมชั่นนี้ เรียบร้อย!! ขอบคุณค่ะ');
            //     $("#show_check").addClass('text-success');
            //     $("#code_promo").addClass('is-valid');
            //     if (data.discout_promo_bath < 0) {
            //         $(".total_check").html('฿ ' + 0);
            //         $("input[name=total_end]").val(0);
            //     } else {
            //         $(".total_bath").html('฿ ' + data.total_bath);
            //         $(".total_check").html('฿ ' + data.discout_promo_bath);
            //         $(".discount_check").html('฿ ' + data.discout_bath);
            //
            //         $("input[name=total_after]").val(data.total_bath);
            //         $("input[name=total_discount]").val(data.discout_bath);
            //         $("input[name=total_end]").val(data.discout_promo_bath);
            //     }
            //
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-success');
            //     }, 5000);
            // } else if (data.condition == 3) {
            //     $("#show_check").html('เงื่อนไขไม่ตรงกับการใช้ code promotion นี้');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // } else if (data.condition == 4) {
            //     $("#show_check").html('ยังไม่ถึงเวลาที่กำหนดสำหรับ Code Promotion นี้');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // } else if (data.condition == 5) {
            //     $("#show_check").html('เกินเวลาที่กำหนดสำหรับ Code Promotion นี้');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // } else if (data.condition == 6) {
            //     $("#show_check").html('ไม่สามารถใช้งานได้...ราคารวมสินค้าน้อยเกินไป!!');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // }else if(data.condition == 7){
            //     $("#show_check").html('รหัสส่วนลดใช้ได้กับการซื้อครั้งละ 1 voucher เท่านั้น!!');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // }else if (data.condition == 8) {
            //     $("#show_check").html('code promotion ถึงจำนวนที่กำหนด');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // } else {
            //     // console.log(data.discout_promo_bath);
            //     $("#show_check").html('โค้ดโปรโมชั่นนี้...ได้ถูกใช้งานไปแล้ว!!');
            //     $("#show_check").addClass('text-danger');
            //     $("#code_promo").addClass('is-invalid');
            //     $("#code_promo").val("");
            //     $(".total_check").html('฿ ' + data.discout_promo_bath);
            //     $("input[name=total_end]").val(data.discout_promo_bath);
            //     setInterval(function () {
            //         $("#show_check").html('');
            //         $("#show_check").removeClass('text-danger');
            //         $("#code_promo").removeClass('is-invalid');
            //     }, 5000);
            // }


            // }
            // });
        });
        $(document).ready(function () {
            $(document).tooltip();
            var src = "{{ url('/autocompleteampuhers') }}";
            $('#ampuhers').autocomplete({
                source: src
            });
            window.search = function () {
                var a = document.getElementById('ampuhers').value;
                confirm("You tried to search " + a + "!");
            };
        });
        $(document).ready(function () {
            $(document).tooltip();
            var src = "{{ url('/autocompletedistricts') }}";
            $('#districts').autocomplete({
                source: src
            });
            window.search = function () {
                var a = document.getElementById('districts').value;
                confirm("You tried to search " + a + "!");
            };
        });
    </script>
    <script>
        $(document).ready(function () {
            fbq('track', 'InitiateCheckout', {currency: 'THB', value: '{{ $grandTotal }}'})
        });

    </script>
@endsection