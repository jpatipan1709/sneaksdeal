<!DOCTYPE html>
<html lang="en">
<head>
    
    
    <title>Sneaksdeal จองดีลที่พัก ราคาถูก</title>
    @yield('meta')
    @include('layouts.head')
    @include('layouts.style')
</head>

<body style="background-color: #f3f7ff;font-family: 'Kanit', sans-serif;" >
@include('layouts.menubar')
@yield('contentFront')

<div class="bot5rem"></div>
@include('layouts.footer')

@yield('scriptFront')

<script>
    $(document).ready(function () {
        // $("#voucher").addClass("active");
    });

    //   $('div.alert').delay(2000).slideUp(500);
</script>
</body>

</html>


