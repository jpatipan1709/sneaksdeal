@extends('layouts.components')
@section('contentFront')
<div class="container">
<style>
.modal-open .modal {
    overflow-x: hidden;
    overflow-y: hidden;
}

.modal-body{
    max-height: calc(106.5vh - 200px);
    overflow-y: auto;

}
.modal-dialog{
    margin-top:50px;
}
</style>
    <form action="{{  url('/sign-up')}}" method="post" id="form-add-register">
        @csrf
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 toplogin ">
                <div class="boxlogin">
                    <div class="headtext" style="color:  #3D3D3D;">สร้างบัญชีผู้ใช้</div>
                    @if(\Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                        <li>{{ \Session::get('success') }}</li>
                    </div><br />
                    @endif
                    @if(\Session::has('danger'))
                    <div class="alert alert-danger alert-dismissible">
                        <li>{{ \Session::get('danger') }}</li>
                    </div><br />
                    @endif
                    {{-- @if($errors->all())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif --}}
                    <div class="row">
                        <div class="col-12 col-md-6 d-block">
                            <div class="travelnormaltext top1rem">ชื่อจริง</div>
                            <input type="text" class="form-control top0rem" id="firstName" name="firstName" placeholder="ชื่อจริง"
                                style="font-family:  kanit;" value="{{ old('firstName') }}">
                            @if ($errors->has('firstName'))
                            <span class="text-danger text-size-error" role="alert">
                                {{ $errors->first('firstName') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-12 col-md-6 d-block">
                            <div class="travelnormaltext top1rem">นามสกุล</div>
                            <input type="text" class="form-control top0rem" id="lastName" name="lastName" placeholder="นามสกุล"
                                style="font-family:  kanit;" value="{{ old('lastName') }}">
                            @if ($errors->has('lastName'))
                            <span class="text-danger text-size-error" role="alert">
                                {{ $errors->first('lastName') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-12 col-md-6 d-block d-sm-none">
                            <div class="travelnormaltext top1rem">ชื่อจริง</div>
                            <input type="text" class="form-control top0rem" id="firstName2"  name="firstName2" placeholder="ชื่อจริง" style="font-family:  kanit;" value="{{ old('firstName2') }}">
                            @if ($errors->has('firstName2'))
                                <span class="text-danger" role="alert">
                                   {{ $errors->first('firstName2') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-12 col-md-6 d-block d-sm-none">
                            <div class="travelnormaltext top1rem">นามสกุล</div>
                            <input type="text" class="form-control top0rem" id="lastName2" name="lastName2" placeholder="นามสกุล" style="font-family:  kanit;" value="{{ old('lastName2') }}">
                            @if ($errors->has('lastName2'))
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('lastName2') }}
                                </span>
                            @endif
                        </div>
                    </div> -->


                    <div class="travelnormaltext top1rem">เบอร์โทรศัพท์</div>
                    <input type="text" class="form-control top0rem" id="tel" name="tel" placeholder="กรุณากรอกเบอร์โทรศัพท์"
                        style="font-family:  kanit;" maxlength="10" minlength="8" value="{{ old('tel') }}" required>
                    @if ($errors->has('tel'))
                    <span class="text-danger text-size-error" role="alert">
                        {{ $errors->first('tel') }}
                    </span>
                    @endif

                    <div class="travelnormaltext top1rem">อีเมล์</div>
                    <input type="email" class="form-control top0rem"  pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,63}$" id="email" name="email" placeholder="กรุณาระบุอีเมลของคุณ"
                        style="font-family:  kanit;" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                    <span class="text-danger text-size-error" role="alert">
                        {{ $errors->first('email') }}
                    </span>
                    @endif
                    <div class="travelnormaltext top1rem">รหัสผ่าน</div>
                    <input type="password" class="form-control top0rem" id="password" name="password" placeholder="กรุณาระบุรหัสผ่านเป็นภาษาอังกฤษหรือตัวเลขอย่างน้อย 8 อักษร"
                        style="font-family:  kanit;">
                    @if ($errors->has('password'))
                    <span class="text-danger text-size-error" role="alert">
                        {{ $errors->first('password') }}
                    </span>
                    @endif
                    <div class="travelnormaltext top1rem">ยืนยันรหัสผ่าน</div>
                    <input type="password" class="form-control top0rem" id="re_password" name="re_password" placeholder="กรุณาระบุรหัสผ่านเป็นภาษาอังกฤษหรือตัวเลขอย่างน้อย 8 อักษร"
                        style="font-family:  kanit;">
                    @if ($errors->has('re_password'))
                    <span class="text-danger text-size-error" role="alert">
                        {{ $errors->first('re_password') }}
                    </span>
                    @endif
                    <br>
    </form>
    <p style="font-size: 0.8em;"><input type="checkbox" class="check_agree" name="check_agree" id="check_agree" value="on"  {{ old('check_agree') == 'on' ? 'checked' : '' }}>
        ในการสร้างบัญชีฉันยอมรับ <strong><a href="#" data-toggle="modal" data-target="#exampleModal">ข้อตกลงการใช้งานและนโยบายความเป็นส่วนตัว</a></strong></p>
    <a class="btn btn-md btn-block top1rem add_register" id="add_register" style="background-color: #488BF8;color: #FFFFFF;font-size:  18px;font-family: kanit;">สร้างบัญชีผู้ใช้</a>

    <div class="travelnormaltext top1rem text-center">หากมีบัญชีอยู่แล้ว<span>
            <a href="{{ url('/sign-in') }}" style="font-weight: 600;color: #6b9cff;">&nbsp;&nbsp;เข้าสู่ระบบ</a></span></div>

</div>


</div>

</div>

</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
           
            <div class="modal-body" style="font-size: 14px;">
                ่       <h4 class="text-center">ข้อกําหนด และเงื่อนไขการใช้งานเว็บไซต์ Sneaksdeal</h4>
                <br>

                <strong>1. การจองโรงแรม และราคา</strong>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1 ทุกการจองโรงแรมที่ทําขึ้นผ่านทาง Sneaksdeal จะได้รับการยืนยันโดยทันที
                <strong>เมื่อท่านเสร็จกระบวนการจองโรงแรม</strong> <span style="color:red;"><strong>ท่านจะได้รับอีเมลยืนยันการจอง</strong></span>
                และจะสามารถพิมพ์บัตรออกมาเพื่อนําไปแสดงต่อโรงแรม ถ้าการบริการใดๆ
                ที่ท่านต้องการไม่ได้กล่าวถึงในรายการการบริการต่างๆ
                กรุณาตรวจสอบเงื่อนไขของโรงแรมหรือติดต่อแผนกบริการลูกค้าของ Sneaksdeal
                โดยทางเราจะจัดเตรียมใบกํากับสินค้าและใบเสร็จรับเงินที่มีรายละเอียดทั้งหมดซึ่งจะถูกส่งไปให้ท่านทางอีเมล
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2 อัตราราคาต่างๆขึ้นอยู่กับจํานวนห้องว่างในขณะที่ทําการจอง <strong>โดยราคาสามารถเปลี่ยนแปลงได้</strong>
                ขึ้นอยู่กับเว็บไซต์จองโรงแรมต่อห้องตลอดการเข้าพักของท่าน
                และจะถูกแสดงโดยคํานวณพร้อมภาษีมูลค่าเพิ่มและภาษีทั้งหมด
                (เนื้อหาอาจเปลี่ยนแปลงได้โดยไม่ต้องแจ้งให้ทราบล่วงหน้า)อย่างไรก็ตาม
                อัตราราคาเหล่านี้อาจมีข้อจํากัดและเงื่อนไขพิเศษ
                ในกรณีนี้กรุณาตรวจสอบห้องและรายละเอียดของอัตราราคาค่าห้องอย่างละเอียดสําหรับเงื่อนไขใดๆ
                <br>
                <br>
                <strong>2. การชําระเงิน</strong>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1
                คุณยอมรับและตกลงว่าบัตรเดบิตหรือเครดิตของคุณจะถูกเรียกเก็บเงินเต็มจํานวนโดยบริษัทของ 2C2P
                ตามราคาการจองทั้งหมดในเวลาที่ทําการจอง ซึ่งมีราคาแสดงอยู่บนเว็บไซต์และค่าธรรมเนียมบริการ (หากมี)
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.2 ในกรณีที่คุณจองที่พัก คุณตกลงและยอมรับว่าค่าบริการ
                ภาษีและค่าใช้จ่ายอื่นๆ
                ทั้งหมดที่เกี่ยวข้องกับการจองของคุณจะสามารถชําระได้โดยคุณให้กับผู้ให้บริการที่พักเมื่อมีการร้องขอจากผู้ให้บริการที่พัก
                (โดยส่วนมากจะเป็นเวลาเช็คอินหรือเช็คเอาท์จากที่พัก)
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.3
                คุณไม่สามารถยกเลิกหรือเปลี่ยนแปลงการจองโรงแรมที่ต้องชําระเงินล่วงหน้าของคุณได้
                <br>
                <br>
                <strong>3. เงื่อนไขสําหรับการใช้คูปองส่วนลด (โค้ดส่วนลด)</strong>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1
                คูปองนี้จะสามารถใช้ได้เฉพาะในระยะเวลาการแลกใช้เมื่อทําการจองบนเว็บไซต์เท่านั้น และไม่ใช่บนไซต์อื่นของ
                Sneaksdeal
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.2 คูปองหนึ่งใบสามารถใช้ได้ต่อการทํารายการหนึ่งรายการเท่านั้น
                ในการจองห้องพักหลายห้องคูปองจะมีผลใช้เฉพาะกับห้องที่มีราคาตํ่าสุดในการทํารายการ
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.3 คูปองนี้ไม่สามารถนําไปใช้กับการจองใด ๆ
                ที่กระทําก่อนหน้านี้ได้คูปองนี้ไม่มีมูลค่าเป็นเงินสดและไม่สามารถขอคืนหรือแลกคืนเป็นเงินสดได้อีกทั้งจะไม่มีมูลค่าคงเหลือและไม่มีการออกเครดิตให้หากราคาที่ซื้อมีจํานวนน้อยกว่าส่วนลดที่ออกให้
                คูปองนี้ไม่สามารถนํามาใช้ซํ้าได้แม้แต่ในกรณีที่คุณเปลี่ยนแปลงหรือยกเลิกการจอง
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.4 คูปองนี้ไม่สามารถนําไปใช้หรือใช้ร่วมกับ คูปอง โปรโมชั่น
                หรือข้อเสนอพิเศษอื่น ๆ ได้
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.5 คูปองนี้ไม่สามารถโอนหรือจําหน่ายได้
                คูปองที่ได้รับผ่านช่องทางที่ไม่ได้รับอนุญาต มีการตัดทอน ปรับเปลี่ยน ทําสําเนา ปลอมแปลง ทําให้เสียหาย
                บงการ หรือก้าวก่ายไม่ว่าในลักษณะใดก็ตาม ถือว่าเป็นโมฆะ
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-147734154-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-147734154-1');
</script>
<script>
    $(".add_register").click(function () {
        var checkbox = $('input.check_agree').is(':checked');

        if (checkbox) {
            fbq('track', 'CompleteRegistration', {content_name: 'register', value: 'success', currency: 'THB'})
           $("#form-add-register").submit();
        }else{
            swal("กรุณายอมรับ ข้อตกลงการใช้งาน และนโยบายความเป็นส่วนตัว");
        }
    });

    $('input[name=email]').bind('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9.,' *'@!#$%&'*+/=?^()_`{|}~-]{1,}$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    $('input[name=tel]').bind('keypress', function (event) {
        var regex = new RegExp("^[0-9-]{1,}$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });
</script>
@endsection
@section('scripts')

@endsection