@extends('layouts.components')
@section('contentFront')

    <div class="container">
    <form action="{{ url('/chklogin') }}" method="post" >
            @csrf
        <div class="row boxlogin toplogin">
            <div class="col-12 col-md-7 ">
                @if(\Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                    <li>{{ \Session::get('success') }}</li>
                </div><br />
                @endif
                @if(\Session::has('danger'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                        <li>{{ \Session::get('danger') }}</li>
                    </div><br />
                    @endif
                <div class="headtext" style="color:  #3D3D3D;">เข้าสู่ระบบ</div>
                <div class="travelnormaltext top1rem">อีเมล์*</div>
                <input type="email" class="form-control top0rem" id="email" name="email" placeholder="โปรดป้อนอีเมลของคุณ" style="font-family:  kanit;" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="text-danger" role="alert">
                        {{ $errors->first('email') }}
                    </span>
                @endif
                <div class="travelnormaltext top1rem">รหัสผ่าน*</div>
                <input type="password" class="form-control top0rem" id="password" name="password" placeholder="กรุณาระบุรหัสผ่าน" style="font-family:  kanit;" value="{{ old('password') }}">
                @if ($errors->has('password'))
                    <span class="text-danger" role="alert">
                        {{ $errors->first('password') }}
                    </span>
                 @endif
                <a href="{{ url('/forgotpassword') }}">
                    <div class="travelnormaltext top0rem text-right" style="color: #6b9cff;font-weight: 600;">ลืมรหัสผ่าน</div>
                </a>
            </div>

            <div class="col-12 col-md-5 ">
                <div class="travelnormaltext top1rem text-right">สมาชิกใหม่?<span>
                            <a href="{{ url('/regis') }}" style=" font-weight: 600;color: #6b9cff;">&nbsp;&nbsp;ลงทะเบียน</a>
                            </span><span>&nbsp;&nbsp;ที่นี่</span>
                </div>
                <button class="btn btn-md btn-block top2rem" type="submit" style="background-color: #FBDC07;color: #ffffff;font-size:  18px;font-weight: 600;font-family: kanit;">เข้าสู่ระบบ</button>

                <div class="travelnormaltext top1rem text-left">หรือเข้าสู่ระบบด้วย
                    <div>

                        <a class="btn btn-md btn-block top1rem" href="{{ route('facebook.login') }}" role="button"
                        style="background-color: #4267B2;color: white;font-family: kanit;font-size: 18px;"><i
                                    class="fab fa-facebook-f"></i>&nbsp;&nbsp;facebook</a>


                        <a class="btn btn-md btn-block top0rem bot1rem" href="{{ route('google.login') }}" role="button"
                        style="background-color: #FC4343;color: white;font-family: kanit;font-size:  18px;"><i
                                    class="fab fa-google-plus-g"></i>&nbsp;&nbsp;Google</a>
                    </div>
                </div>
            </div>
        </div>  
    </form>

</div>

@endsection