@extends('layouts.components')
@section('contentFront')
    <div class="container">
          <div class="row">
              <div class="col top2rem">
                    @if(\Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                        <li>{{ \Session::get('success') }}</li>
                    </div><br />
                    @endif
              </div>
          </div>
        <div class="row">
               
            <div class="col-lg-6 col-md-6  border-right">

                <div class="card bg-dark text-white">
                    <img class="card-img" src="{{ asset('storage/joinus').'/'.$joinus_content->juc_images1 }}" alt="Card image">
                    <div class="card-img-overlay">
                        <div class="headtext text-center hoteltop" style="color:  white;">{{ $joinus_content->juc_text1 }}
                        </div>
                    </div>
                </div>

                <div class="headtext top1rem">ร่วมเป็นส่วนหนึ่งของเรา</div>

                <form action="{{ url('/addjoin') }}" class="shake" role="form" method="post" id="contactForm" name="contact-form" data-toggle="validator">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6 col-xs-12 top1rem">
                            <input type="text" class="form-control" name="name" placeholder="ชื่อ" style="font-family:  kanit;" value="{{ old('name') }}"> 
                            @if ($errors->has('name'))
                                <span class="text-danger text-size-error" role="alert">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6 col-xs-12 top1rem">
                            <input type="text" class="form-control"  name="hotel" placeholder="โรงแรม" style="font-family:  kanit;" value="{{ old('hotel') }}">
                            @if ($errors->has('hotel'))
                                <span class="text-danger text-size-error" role="alert">
                                    {{ $errors->first('hotel') }}
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-md-6 col-xs-12 top1rem">
                            <input type="text" class="form-control" name="tel" placeholder="เบอร์โทร" style="font-family:  kanit;" maxlength="10" value="{{ old('tel') }}">
                            @if ($errors->has('tel'))
                            <span class="text-danger text-size-error" role="alert">
                                {{ $errors->first('tel') }}
                            </span>
                         @endif
                        </div>
                        <div class="col-md-6 col-xs-12 top1rem">
                            <input type="email" class="form-control" name="mail" placeholder="อีเมล" style="font-family:  kanit;" value="{{ old('mail') }}">
                            @if ($errors->has('mail'))
                            <span class="text-danger text-size-error" role="alert">
                                {{ $errors->first('mail') }}
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="wow slideInRight" data-wow-delay="0.5s">
                        <div class="form-row">
                            <div class="col-md-12 col-xs-12 top1rem">
                                <textarea class="form-control" name="comment" rows="6" id="comment" placeholder="คอมเมนต์">{{ old('comment') }}</textarea>
                                @if ($errors->has('comment'))
                                <span class="text-danger text-size-error" role="alert">
                                    {{ $errors->first('comment') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="wow slideInRight" data-wow-delay="0.6s">
                        <button href="#" type="submit" class="btn btn-block top1rem" role="button" aria-pressed="true" style="padding-left: 15px;color:  white;font-size: 1rem;background-color: #4e4e4e;border-color: #4e4e4e;border-radius: 5px;font-family: 'Kanit', sans-serif;">ส่งข้อความ</button>
                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                        <div class="clearfix"></div>
                    </div>

                </form>

                <div class="headtext top1rem">หรือติดต่อเรา</div>
                <div class="travelnormaltext ">E-mail:  {{ $joinus_content->juc_mail1	 }} <br>
                    Phone:  {{ $joinus_content->juc_tel1	 }} 
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="card bg-dark text-white">
                    <img class="card-img" src="{{ asset('storage/joinus').'/'.$joinus_content->juc_images2 }}" alt="Card image">
                    <div class="card-img-overlay">
                        <div class="headtext text-center hoteltop" style="color:  white;">{{ $joinus_content->juc_text2 }}
                        </div>
                    </div>
                </div>

                <div class="box02 top2rem">
                    <div class="headtext text-center joinustop">ร่วมงานกับเรา</div>
                    <div class="travelnormaltext text-center ">
               
                        {!!$joinus_content->juc_content !!}
                        
                    </div>
                    <div class="joinusbot"></div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-12">
                <div class="border-bottom"></div>
            </div>


            <div class="col-lg-5 col-md-6 top1rem">


                <div class="row">
                    <div class="col-12">
                        <div class="headtext top1rem">ติดต่อเรา</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="travelnormaltext top0rem" style="color: #4E4E4E;">
                                {!! $joinus_content->juc_contact !!} 
                        </div>
                    </div>
                </div>

                <div class="row top2rem">
                    <div class="col-2">
                        <img src="img/joinus/icon01.png" class="img-fluid" alt="Responsive image">
                    </div>

                    <div class="col-9 padding0" style="margin-top: -3px;">
                        <div class="travelnormaltext" style="color: #4E4E4E;">   {{ $joinus_content->juc_address }} 
                        </div>
                    </div>
                </div>

                <div class="row top1rem">
                    <div class="col-2">
                        <img src="img/joinus/icon02.png" class="img-fluid" alt="Responsive image">
                    </div>

                    <div class="col-9 padding0" style="margin-top: -3px;">
                        <div class="travelnormaltext" style="color: #4E4E4E;">โทร : {{ $joinus_content->juc_tel2 }} 
                        </div>
                    </div>
                </div>

                <div class="row top1rem">
                    <div class="col-2">
                        <img src="img/joinus/icon03.png" class="img-fluid" alt="Responsive image">
                    </div>

                    <div class="col-9 padding0" style="margin-top: -3px;">
                        <div class="travelnormaltext" style="color: #4E4E4E;">อีเมล : {{ $joinus_content->juc_mail2 }} 
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-lg-7 col-md-6 top2rem ">

                    {!! $joinus_content->juc_map !!} 

            </div>

        </div>

    </div>



@endsection

@section('scriptFront')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-147734154-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-147734154-1');
</script>
    <script>
        $(document).ready(function () {
            $("#joinus").addClass("active");
            // $("iframe").css("width",100%);
            $("iframe").width('100%');
            $('iframe').css('height', '350px');
        });
    </script>
@endsection
