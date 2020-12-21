<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Font Awesome -->
    <link type="image/ico" rel="shortcut icon" href="{{ URL::asset("backoffice/dist/img/settings.png") }}">
    <!-- Ionicons --> {{--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">--}}
    <link rel="stylesheet"
          href="{{URL::asset('backoffice/dist/ionicons.min.css')}}">
    {{--<script src="https://unpkg.com/ionicons@4.4.1/dist/ionicons.js"></script>--}}
    {{--    <link rel="stylesheet" href="{{URL::asset('backoffice/plugins/font-awesome/css/font-awesome.min.css') }}">--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">    <!-- Theme style -->
    <link rel="stylesheet" href="{{URL::asset('backoffice/plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset("backoffice/dist/css/adminlte.min.css")}}">    <!-- iCheck -->
    <link rel="stylesheet" href="{{URL::asset("backoffice/plugins/iCheck/flat/blue.css")}}">    <!-- Morris chart -->
    <link rel="stylesheet" href="{{URL::asset("backoffice/plugins/morris/morris.css")}}">    <!-- jvectormap -->
    <link rel="stylesheet" href="{{URL::asset("backoffice/plugins/jvectormap/jquery-jvectormap-1.2.2.css")}}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{URL::asset("backoffice/plugins/datepicker/datepicker3.css")}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{URL::asset("backoffice/plugins/daterangepicker/daterangepicker-bs3.css")}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{URL::asset("backoffice/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css")}}">
    <!-- Google Font: Source Sans Pro --> {{--<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">--}}
    <link href="{{URL::asset("backoffice/dist/family_source.css")}}" rel="stylesheet">    <!-- CSS -->
    <link rel="stylesheet" href="{{URL::asset('backoffice/plugins/alertifyjs/css/alertify.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('backoffice/plugins/alertifyjs/css/themes/default.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('backoffice/plugins/alertifyjs/css/themes/semantic.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('backoffice/plugins/alertifyjs/css/themes/bootstrap.min.css')}}"/>
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet">


    {{-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{URL::asset('backoffice/plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->{{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>--}}
    <script src="{{URL::asset('backoffice/dist/jquery-ui.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>

    <script src="{{URL::asset('backoffice/plugins/alertifyjs/alertify.min.js')}}"></script>







    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

    @include("backoffice/layout/style")
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include("backoffice/layout/inc_topmenu")
    @include("backoffice/layout/inc_leftmenu")
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0 text-dark"> @yield('top1') </h1></div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">@yield('top2')</a></li>
                            <li class="breadcrumb-item active">@yield('top3')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @yield('contents')
            </div>
        </section>
    </div>
    <footer class="main-footer"><strong>@ 2018 <a href="#">Sneaksdeal</a>.</strong>
        <div class="float-right d-none d-sm-inline-block"><b>Backoffice</b></div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark">   </aside>
</div>@yield('script')

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>    $.widget.bridge('uibutton', $.ui.button)</script><!-- Bootstrap 4 -->
<script src="{{URL::asset('backoffice/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Morris.js charts -->{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>--}}
<script src="{{URL::asset('backoffice/dist/raphael-min.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/morris/morris.min.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{URL::asset('backoffice/plugins/knob/jquery.knob.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
{{--<script src="{{URL::asset('backoffice/dist/moment.min.js)}}"></script>--}}
<script src="{{URL::asset('backoffice/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{URL::asset('backoffice/plugins/filestyle/src/bootstrap-filestyle.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{URL::asset('backoffice/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/fastclick/fastclick.js')}}"></script>
<script src="{{URL::asset('backoffice/dist/js/adminlte.js')}}"></script>
{{--<script src="{{URL::asset('backoffice/dist/js/pages/dashboard.js')}}"></script>--}}<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('backoffice/dist/js/demo.js')}}"></script>
<script src="{{URL::asset('backoffice/plugins/select2/select2.full.min.js')}}"></script>
<script>    jQuery(document).ready(function ($) {
        $('.select2').select2()
        $(".filestyle").filestyle();
    });

    function go_back() {
        window.history.back();
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview2').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function alertDelete(funtions) {
        alertify.confirm('!Delete Data', "Do you want delete this data?", function () {
            funtions
        }, function () {
            alertify.error('cancel');
        });
    }</script>
<script src="{{ URL::asset('assets/webshim/js-webshim/minified/polyfiller.js') }}"></script>
<script src="{{ URL::asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>

<script>
    webshim.setOptions('forms', {
        lazyCustomMessages: true,
        replaceValidationUI: true,
        addValidators: true
    });

    (function () {
        var stateMatches = {
            'true': true,
            'false': false,
            'auto': 'auto'
        };
        var enhanceState = (location.search.match(/enhancelist\=([true|auto|false|nopolyfill]+)/) || ['', 'auto'])[1];

        webshim.ready('jquery', function () {
            $(function () {
                $('.polyfill-type select')
                    .val(enhanceState)
                    .on('change', function () {
                        location.search = 'enhancelist=' + $(this).val();
                    })
                ;
            });
        });
        webshim.setOptions('forms', {
            customDatalist: stateMatches[enhanceState]
        });
        webshim.setOptions('forms-ext', {
            replaceUI: stateMatches[enhanceState]
        });
    })();

    // load the forms polyfill

    webshim.polyfill('forms forms-ext');

</script>
</body>
</html>