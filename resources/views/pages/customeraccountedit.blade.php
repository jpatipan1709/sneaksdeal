@extends('layouts.components')
@section('contentFront')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="headtext top2rem">จัดการบัญชีของฉัน</div>
            </div>
            <div class="col-lg-4 col-md-5 top2rem">
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">ข้อมูลส่วนตัว</div>
                        <a href="{{ route('customeraccount.edit',Session::get('id_member')) }}?status=profile"
                           style="color:  #3D3D3D;">
                            <div class="minipricetext" style="color: #6b9cff;">แก้ไข</div>
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">ชื่อ-นามสกุล</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->name_member.'
                        '.$members->lastname_member }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">เบอร์โทรศัพท์</div>
                        </div>
                        @php
                            function phone_number_format($number) {
                            $number = preg_replace("/[^\d]/","",$number);
                            $length = strlen($number);
                            if($length == 10) {
                            $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
                            }
                            return $number;
                            }
                        @endphp
                        <div class="travelnormaltext" style="color:  #707070;">{{ phone_number_format($members->tel_member)
                        }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">อีเมล</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->email }}</div>
                    </li>
                </ul>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">ข้อมูลที่อยู่</div>
                        <a href="{{ route('customeraccount.edit',Session::get('id_member')) }}"
                           style="color:  #3D3D3D;">
                            {{--<div class="minipricetext" style="color: #6b9cff;">แก้ไข</div>--}}
                        </a>
                        <a href="{{ route('customeraccount.edit',Session::get('id_member')) }}?status=address"
                           style="color:  #3D3D3D;">
                            <div class="minipricetext" style="color: #6b9cff;">แก้ไข</div>
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">ที่อยู่</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->address_member }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">ตำบล/แขวง</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->districts_id }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">อำเภอ/เขต</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->amphures_id }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">จังหวัด</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->p_name }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">รหัสไปรษณีย์</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->zip_code }}</div>
                    </li>
                </ul>
            </div>


            <div class="col-lg-8 col-md-7 top2rem">

                <div class="miniheadtext top0rem" style="color:  #3D3D3D;">แก้ไขข้อมูล</div>
                <div class="list-group-item top1rem">
                    <form action="{{ route('customeraccount.update',$members->id_member) }}" method="post">
                        @csrf
                        <input type="hidden" value="{{$status}}" name="status">
                        {{ method_field('PUT') }}
                        <div class="row top1rem">
                            <div class="col-12">
                                @if(\Session::has('success'))
                                    <div class="alert alert-success">
                                        <li>{{ \Session::get('success') }}</li>
                                    </div><br/>
                                @endif
                                @if(\Session::has('danger'))
                                    <div class="alert alert-danger">
                                        <li>{{ \Session::get('danger') }}</li>
                                    </div><br/>
                                @endif
                            </div>
                            @if($status  != 'address')
                                <div class="col-md-6 mb-3">
                                    <div class="travelnormaltext">ชื่อ</div>
                                    <input type="text" class="form-control top0rem" id="firstName" name="firstName"
                                           placeholder="ชื่อ"
                                           style="font-family:  kanit;" value="{{ $members->name_member }}">
                                    @if ($errors->has('firstName'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('firstName') }}
                            </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="travelnormaltext">นามสกุล</div>
                                    <input type="text" class="form-control top0rem" id="lastName" name="lastName"
                                           placeholder="นามสกุล"
                                           style="font-family:  kanit;" value="{{ $members->lastname_member }}">
                                    @if ($errors->has('lastName'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('lastName') }}
                            </span>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="travelnormaltext">โทรศัพท์</div>
                                    <input type="number" class="form-control top0rem" id="tel" name="tel"
                                           placeholder="กรุณากรอกเบอร์โทรศัพท์"
                                           style="font-family:  kanit;" value="{{ $members->tel_member }}" required>
                                    @if ($errors->has('tel'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('tel') }}
                            </span>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="travelnormaltext">อีเมล</div>
                                    <input type="text" class="form-control top0rem" id="email" name="email"
                                           placeholder="กรุณาระบุอีเมลของคุณ"
                                           style="font-family:  kanit;" value="{{ $members->email }}" readonly>
                                    @if ($errors->has('email'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('email') }}
                            </span>
                                    @endif
                                </div>
                                {{-- {{ dd(Session::get('google_id')) }} --}}
                                @if (Session::get('facebook_id') == null && Session::get('google_id') == null)
                                    <div class="col-md-6 mb-3">
                                        <div class="travelnormaltext">รหัสผ่านเดิม</div>
                                        <input type="password" class="form-control top0rem" id="current_password"
                                               name="current_password" placeholder="กรุณาระบุรหัสผ่าน"
                                               style="font-family:  kanit;" value="{{ old('current_password') }}">
                                        @if ($errors->has('current_password'))
                                            <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('current_password') }}
                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="travelnormaltext">รหัสผ่านใหม่</div>
                                        <input type="password" class="form-control top0rem" id="new_password"
                                               name="new_password" placeholder="กรุณาระบุรหัสผ่านใหม่"
                                               style="font-family:  kanit;" value="">
                                        @if ($errors->has('new_password'))
                                            <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('new_password') }}
                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="travelnormaltext">ยืนยันรหัสผ่านใหม่</div>
                                        <input type="password" class="form-control top0rem" id="confirm_new_password"
                                               name="confirm_new_password" placeholder="กรุณายืนรหัสผ่านใหม่"
                                               style="font-family:  kanit;" value="">
                                        @if ($errors->has('confirm_new_password'))
                                            <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('confirm_new_password') }}
                            </span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="col-md-12 mb-3">
                                    <div class="travelnormaltext">อีเมลสำหรับรับข้อมูล Voucher</div>
                                    <input type="text" class="form-control top0rem" id="email_send_order"
                                           name="email_send_order"
                                           placeholder="กรุณาระบุที่อยู่ของคุณ"
                                           style="font-family:  kanit;" value="{{ $members->email_send_order }}">
                                    @if ($errors->has('email_send_order'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('email_send_order') }}      </span>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="travelnormaltext">ที่อยู่</div>
                                    <input type="text" class="form-control top0rem" id="address" name="address"
                                           placeholder="กรุณาระบุที่อยู่ของคุณ"
                                           style="font-family:  kanit;" value="{{ $members->address_member }}">
                                    @if ($errors->has('address'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('address') }}
                            </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="travelnormaltext">แขวง/ตำบล</div>
                                    <div class="top0rem"></div>
                                    <input type="text" class="form-control top0rem" id="districts" name="districts"
                                           placeholder="เลือกแขวง/ตำบลของท่าน"
                                           style="font-family:  kanit;" value="{{ $members->districts_id }}">
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
                                <div class="col-md-6 mb-3">
                                    <div class="travelnormaltext">เขต/อำเภอ</div>
                                    <div class="top0rem"></div>
                                    <input type="text" class="form-control top0rem" id="ampuhers" name="ampuhers"
                                           placeholder="เลือกเขต/อำเภอของท่าน"
                                           style="font-family:  kanit;" value="{{ $members->amphures_id }}">
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
                                    <select class="simple-select2 w-100 province" name="province">
                                        <optgroup label="จังหวัด">
                                            <option value="0">เลือกจังหวัดของท่าน</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}" @if(($members->provinces_id)==($province->id))
                                                    {{ 'selected' }} @endif >{{ $province->name_th }}</option>
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
                                    <input type="text" class="form-control top0rem postcode" id="postcode"
                                           name="postcode"
                                           placeholder="กรุณาระบุรหัสไปรษณีย์" style="font-family:  kanit;"
                                           value="{{ $members->zip_code }}">
                                    @if ($errors->has('postcode'))
                                        <span class="text-danger" style="font-size:12px;">
                                {{ $errors->first('postcode') }}
                                          </span>
                                    @endif
                                </div>



                            @endif


{{--                            <div class="row">--}}
                                <div class="offset-md-6 col-md-6 col-12">
                                    <button class="btn btn-md btn-block top1rem" type="submit"
                                            style="background-color: #488BF8;color: #FFFFFF;font-family: kanit;font-size: 18px;">
                                        แก้ไขข้อมูล
                                    </button>
                                </div>
{{--                            </div>--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection





@section('scriptFront')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                    console.log(data);
                    $(".ampuhers").html(data);
                }
            });

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
@endsection