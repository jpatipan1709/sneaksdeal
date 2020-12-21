@extends('layouts.components')
@section('contentFront')
@endsection

<a href="javascript:void(0)">Click</a>
@section('scriptFront')
    <script>
        // addJS_Node (null, null, overrideSelectNativeJS_Functions);
        //
        // function overrideSelectNativeJS_Functions () {
        //     window.alert = function alert (message) {
        //         console.log (message);
        //     }
        // }
        //
        // function addJS_Node (text, s_URL, funcToRun) {
        //     var D                                   = document;
        //     var scriptNode                          = D.createElement ('script');
        //     scriptNode.type                         = "text/javascript";
        //     if (text)       scriptNode.textContent  = text;
        //     if (s_URL)      scriptNode.src          = s_URL;
        //     if (funcToRun)  scriptNode.textContent  = '(' + funcToRun.toString() + ')()';
        //
        //     var targ = D.getElementsByTagName ('head')[0] || D.body || D.documentElement;
        //     targ.appendChild (scriptNode);
        // }
        // var newWin = window.open('','title', "width=200, height=100");
        // function closeWin(newWin) {
        //     newWin.close();
        // }
        // if(!window.open('','title', "width=200, height=100") || window.open('','title', "width=200, height=100").closed || typeof window.open('','title', "width=200, height=100").closed=='undefined')
        // {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: '!กรุณาเปิดอนุญาตการใช้งาน Popup ที่มุมขวาของหน้าจอ',
        //         footer: '<a href>Why do I have this issue?</a>'
        //     })
        //     checkPopup();
        // }else{
        //     closeWin(window.open('','title', "width=200, height=100"))
        // }
        // function checkPopup() {
        //     if(!window.open('','title', "width=200, height=100") || window.open('','title', "width=200, height=100").closed || typeof window.open('','title', "width=200, height=100").closed=='undefined')
        //     {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops...',
        //             text: '!กรุณาเปิดอนุญาตการใช้งาน Popup ที่มุมขวาของหน้าจอ',
        //             footer: '<a href>Why do I have this issue?</a>'
        //         })
        //     }else{
        //         closeWin(window.open('','title', "width=200, height=100"))
        //     }
        // }



        // $('a').on('click.open', function(e) {
        //     e.preventDefault(); window.open('http://disney.com')
        // });

    </script>
@endsection