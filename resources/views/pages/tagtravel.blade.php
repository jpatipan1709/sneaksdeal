@extends('layouts.components')
@section('contentFront')




<div class="container">
    <div class="row mx-auto">
        
            <div class="col-12">
                <div class="headtext top3rem text-center">TAG<span><small> / {{ $blog_tag->name_type }}</small></span></div>
            </div>   
            @foreach($blogs as $key => $blog)
            <div class="col-sm-12 col-md-3 paddingtravel top1rem ">
                <a href="{{ url('travelblogdetail',$blog->id_blog) }}"> 
                    <div class="controlimgbox">
                        <img src="{{ url('storage/blog/'.$blog->img_blog_index) }}" class="img-fluid">
                    </div>

                    <div class="minitraveltext top0rem" style="color: #707070;"> {{ $blog->title_blog  }}
                    </div>
                </a>
            </div>
            @endforeach
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
            $("#travelguide").addClass("active");
        });
    </script>
@endsection