@extends('layouts.components')
@section('contentFront')
    <div class="container">

        <div class="row">

            <div class="col-12">
                <div class="headtext top2rem">How it Work</div>
                {{-- <img class="img-fluid d-block w-100 top1rem" src="img/joinus/career.png"> --}}
                <div class="normaltext top0rem insertImg" style="word-wrap: break-word !important;">
                    @if ($howitworks != null)
                        {!!  str_replace('<p>|page|</p>','',$howitworks->hiw_detail) !!}
                    @endif
                       
                </div>
            
                <script>
                        $(".insertImg img").each(function (){
                            $(this).removeAttr('style');
                            $(this).addClass('img-fluid d-block w-100 top1rem');
                            // $(this).css('padding','0');
                        });
    
                    </script>
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
        $("#work").addClass("active");
    });
</script>
@endsection
