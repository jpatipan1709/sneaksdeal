@php
    session_start();
    if(isset( $_SESSION['idImageSort'])){
        $_SESSION['idImageSort'][] = $_GET['image'];

    }else{
     $_SESSION['idImageSort'] = [];
    $_SESSION['idImageSort'][] = $_GET['image'];
    }

   print_r($_SESSION['idImageSort'])
@endphp