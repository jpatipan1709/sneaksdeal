@extends('layouts.components')
@section('contentFront')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 toplogin">
            <div class="boxlogin">
                @if(\Session::has('danger'))
                <div class="alert alert-danger">
                    <li>{{ \Session::get('danger') }}</li>
                </div><br />
                @endif
                @if(\Session::has('success'))
                <div class="alert alert-success">
                    <li>{{ \Session::get('success') }}</li>
                </div><br />
                @endif
                <div class="miniheadtext" style="color:  #3D3D3D;">ลืมรหัสผ่าน</div>


                <div class="travelnormaltext top1rem">E-mail</div>
                <form action="{{ url('/forgetpassword')}}" method="post">
                    @csrf
                    <input type="email" class="form-control top0rem" id="email" name="email" placeholder="โปรดป้อนอีเมลของคุณ"
                        style="font-family:  kanit;" required>
                    <button class="btn btn-md btn-block top1rem forget_pass" type="submit" role="button" style="background-color: #488BF8;color: #FFFFFF;font-size:  18px;font-family: kanit;">รีเซ็ตรหัสผ่าน</button>
                    <div class="travelnormaltext top1rem text-center">หากมีบัญชีอยู่แล้ว<span>
                            <a href="{{ url('/sign-in') }}" style="font-weight: 600;">&nbsp;&nbsp;เข้าสู่ระบบ</a></span></div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection