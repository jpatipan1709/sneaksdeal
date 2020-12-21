<!DOCTYPE html>
<html lang="en">
<head>


    <title>Sneaksdeal จองดีลที่พัก ราคาถูก</title>
    @yield('meta')
    @include('layouts.head')
    @include('layouts.style')
    <style>/* Absolute Center CSS Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @media (min-width: 1200px){
            .container {
                max-width: 90%!important;
            }
            }

        .pagination {
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        #btnTop {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            cursor: pointer;
            border-radius: 4px;
        }

        .text-cart-main {
            font-family: 'Kanit', sans-serif;
            font-size: 18px;
            color: #454545;
            font-weight: bold;
        }
        .modal-set{
            max-width: 90%!important;
            height:85%!important
        }

       #modalHowItWork{
           overflow: hidden;
         height:90%!important
       }
        #modalHowItWork img{
           max-width: 100%!important;
       } 
        #modalHowItWork div.active{
           display: block;
       }   #modalHowItWork div.hidden{
           display: none;
       }
       #modalHowItWork{
           top:3%!important;
    display: block !important; /* I added this to see the modal, you don't need this */
        }
     #modalHowItWork .modal-content{
           margin-top:30px!important;
        }

/* Important part */

#modalHowItWork .modal-body{
  height: auto;
  max-height: 700px;
  overflow-y: auto;
}
/* checkbox */
.label-checkbox {
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  width: 300px;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.label-checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.label-checkbox:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.label-checkbox input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.label-checkbox input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.label-checkbox .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
.progressbar-step {
      counter-reset: step;
      margin-top: 30px;
  }
  .progressbar-step li {
      list-style-type: none;
      width: 25%;
      float: left;
      font-size: 24px;
      position: relative;
      text-align: center;
      text-transform: uppercase;
      color: #7d7d7d;
  }
  .progressbar-step li:before {
      width: 60px;
      height: 60px;
      content:  counter(step) ". ";
      counter-increment: step;
      line-height: 55px;
      font-weight: bold;
      font-size: 24px
      top:10px;
      border: 2px solid #7d7d7d;
      display: block;
      text-align: center;
      margin: 0 auto 10px auto;
      border-radius: 50%;
      background-color: white;
  }
  .progressbar-step li:after {
      width: 100%;
      height: 3px;
      content: '';
      position: absolute;
      background-color: #7d7d7d;
      top: 30px;
      left: -50%;
      z-index: -1;
  }
  .progressbar-step li:first-child:after {
      content: none;
  }
  .progressbar-step li.active {
      color: #3d3d3d;
  }
  .progressbar-step li.active:before {
      border-color: #FBDC07;
            background-color: #FBDC07;
            color:white

  }
  .progressbar-step li.active + li:after {
      background-color: #FBDC07;
  }
  .mobile{
      display: none;
  }
  .cursor-true{
      cursor: pointer;
  }
@media screen and (max-width: 767px) and (min-width: 200px) {
           .modal-set{
            max-width: 100%!important;
            height:85%!important
        }
         .pc{
            display: none;
         }
        .mobile{
          display: block;
         }
       }

@media screen and (min-width: 801px) and (max-width: 1299px) {
    #modalHowItWork img{
        width: 80%
    }
    /* .btn-wellcome{
        position: absolute;bottom:23%!important;left:38%
    } */
}

    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


</head>

<body style="background-color: #f3f7ff;font-family: 'Kanit', sans-serif;">
 
    {{-- <marquee><h5 style="color: red">ขณะนี้เว็บไซต์ปิดปรับปรุงชั่วคราว</h5></marquee> --}}
@include('layouts.menubar')
@yield('contentFront')
   <!-- Modal -->
   @if(@Session::get('is_activate_hoitwork') == 'no')
   @php
   $getHowItWork = \DB::table('tb_howitwork')->first();
   $getPageHowItWork = explode('<p>|page|</p>',$getHowItWork->hiw_detail);
   @endphp
<div class="modal fade"  id="modalHowItWork" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-set" role="document" >
    <div class="modal-content" >
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle">วิธีการใช้งาน</h5>
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modalHowItWork" >
         <form method="post" id="frmItHowItWork" action="{{url('howitwork')}}">
              @csrf
              <input type="hidden" value="{{url()->current()}}" name="checkHowToItWork">
         </form>
        <div class="row">
            @foreach($getPageHowItWork AS $key => $v)
        <div class="col-12 insertImg page-how-work page-how-work-{{$key+1}} {{($key==0 ? 'active':'hidden')}}" >
        <center>
        {!! str_replace('</p>','',str_replace('<p>','',str_replace('<p>&nbsp;</p>','',$v))) !!}
        </center>
        </div>
        <div class="col-12  page-how-work page-how-work-{{$key+1}} {{($key==0 ? 'active':'hidden')}}" >
        @if(($key+1) == count($getPageHowItWork))
         
        
        <center>
            <div>
              <label class="label-checkbox"> อ่านวิธีการใช้งานเรียบร้อยแล้ว
                <input type="checkbox" id="hotItWorkCheck">
                    <span class="checkmark"></span>
            </label>
            </div>
              <div class="btn-wellcome">
        <button type="button" style="width: 40%;max-width:300px" onclick="nextPreStep({{$key}},{{count($getPageHowItWork)}})"    class="btn btn-default">Previous</button>
        <button type="button" style="width: 40%;max-width:300px"  onclick="submitHowItWork()" class="btn btn-success" id="btnSubmitHowItWork" disabled>WELLCOME TO SNEAKSDEAL.COM</button>
        </div>   
        <br>
            <br>
        </center> 
        @else
        <center>
        <button type="button" style="width: 40%;max-width:300px" onclick="nextPreStep({{$key}},{{count($getPageHowItWork)}})"    class="btn btn-default">Previous</button>
        <button type="button" style="width: 40%;max-width:300px"  onclick="nextPreStep({{$key+2}},{{count($getPageHowItWork)}})" class="btn btn-warning">Next</button> 
            <br>
            <br>
        </center>
          @endif
        </div>
        @endforeach
        </div>
      </div>

    </div>
</div>
<script>
    var i  = 1;
      $(".insertImg img").each(function (){
                            $(this).removeAttr('style');
                            if(i ==  $(".insertImg img").length){
                                                            $(this).attr('style','max-width:590px!important;');
                            }else{
                                                            $(this).attr('style','max-width:650px!important;');
                            }
                            $(this).addClass('img-fluid d-block w-100 top1rem');
                            // $(this).css('padding','0');
     i++;});
     $('#hotItWorkCheck').click(function(){
        if($(this).is(":checked")){
            $('#btnSubmitHowItWork').removeAttr('disabled');
        }else{
       $('#btnSubmitHowItWork').attr('disabled',true);
        }
     });
    function submitHowItWork(){
        $('#frmItHowItWork').submit();
    }

    function nextPreStep(page,max){
        if(page > 0 && page <= max){
             $('.page-how-work').addClass("hidden").removeClass("active");    
            $('.page-how-work-'+page).addClass("active").removeClass("hidden");    
        }
    }
    
    $('#modalHowItWork').modal({ backdrop: 'static' }, 'show' );
    // $('#exampleModalLongTitle').html("Your screen resolution is: " + screen.width + "x" + screen.height)
</script>
@endif

<div id="preloader">
<div class="loading" id="div-loading-page" style="display: none">Loading&#8230;</div>
</div>
<button id="btnTop" onclick="topFunction()" class="btn btn-primary"><i class="fas fa-arrow-up"></i></button>
<div class="bot5rem"></div>



@include('layouts.footer')

@yield('scriptFront')

<style>

</style>
<script>
    //Get the button
    var btnTop = document.getElementById("btnTop");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
            btnTop.style.display = "block";
        } else {
            btnTop.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    // $(window).on("load",function(){
    //     $('#preloader').fadeOut();
    //     $('#div-loading-page').delay(50).fadeOut(100);
    //     $('body').delay(50).css({'overflow': 'visible'});
    // });

    //   $('div.alert').delay(2000).slideUp(500);
</script>
<!-- Facebook Pixel Code -->
<script>
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '205749893977929');
    fbq('track', 'PageView');
</script>
<noscript>
    <img height="1" width="1"
         src="https://www.facebook.com/tr?id=205749893977929&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

</body>

</html>


