@extends('layouts.components')
@section('contentFront')
<style>
    .box:hover {
        box-shadow: 0 0px 0px 0 rgba(0, 0, 0, 0.19);
    }
    </style>
    @php
        if($blogBanner->img_blog_index !=''){
        $img = url('storage/blog/'.$blogBanner->img_blog_index);
        }else{
        $img =  url('img/travelguidedetail/img23.png');
        }
    @endphp

    <div class="container">

        <div class="top2rem"></div>
        <div class="row box">
            <div class="col-12 col-md-6" style="padding:  0px;">

                <a href="travelblogdetail/{{$blogBanner->id_blog}}">
                    <img src="{{$img}}" class="img-fluid">
                </a>

            </div>

            <div class="col-12 col-md-6">
                <div class="headtext top1rem">{{$blogBanner->name_blog}}</div>
                
             
                @php
                    $string = strip_tags($blogBanner->detail_blog);
                    if (strlen($string) > 1400) {
                        $stringCut = substr($string, 0, 1200);
                        $endPoint = strrpos($stringCut, ' ');
                        $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                        $string .= '...';
                    }
                @endphp
                <div class="travelnormaltext top1rem" style="text-indent: 1.5em;">{{  $string }}</div>


                <a class="btn btn-md btnsneakout" href="travelblogdetail/{{$blogBanner->id_blog}}" role="button" style="
    color: white;
">
                    ดูรายละเอียดเพิ่มเติม</a>

                <div class="bottravel"></div>

            </div>

        </div>

        @foreach ($travel_guides as $travel_guide)
        <div class="row box top1rem">
            <div class="col-12">
                <div class="travelheadtext" style="padding-top: 5px;padding-bottom:  7px;">{{ $travel_guide->tg_name  }}
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            @php
                $blog_id = explode(',',$travel_guide->tg_blog_id);
                count($blog_id);
            @endphp
            {{-- @if (count($blog_id) == 0)
                
            @endif --}}
            @foreach($blog_id AS $key => $row)
                @php
                    $blogs = App\Model\admin\BlogModel::where('id_blog',$row)->get();
                @endphp
                @foreach($blogs AS $key2 => $row2)
                @php
                    if($row2->banner_blog !=''){
                    $imgRow = url('storage/blog/'.$row2->banner_blog);
                    }else{
                    $imgRow = url('img/travelguidedetail/img23.png');
                    }
                @endphp
                <div class="col-sm-12 col-md-3 paddingtravel top1rem ">
                        <a href="travelblogdetail/{{$row2->id_blog}}">
                            <div class="controlimgbox">
                                <img src="{{$imgRow}}" class="img-fluid ">
                            </div>
    
                            <div class="minitraveltext top0rem" style="color: #707070;font-size:15px;"><strong>{{ $row2->name_blog }}</strong></div>
                            <div class="minitraveltext" style="color: #707070;">{{ $row2->address_blog }}</div>
                        </a>
                </div>
                @endforeach
            @endforeach
        </div>
        @endforeach

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
