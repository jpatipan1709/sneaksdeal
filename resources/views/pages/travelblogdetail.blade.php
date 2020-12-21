@extends('layouts.components')
@section('meta')
    <meta name="images" property="og:image" content="{{ url('storage/blog/'.$blog->img_blog_index) }} "/>
    <meta name="description" property="og:description"
          content="{{ ($blog->title_blog != '' ? $blog->title_blog :' $blog->title_blog') }}"/>
    <meta name="title" property="og:title" content="{{ $blog->name_blog }}"/>
@endsection
@section('contentFront')

    @php
        if($blog->banner_blog !=''){
        $banner = url('storage/blog/'.$blog->banner_blog);
        }else{
        $banner = url('img/travelguidedetail/img13.png');
        }
        $noV=1;
    @endphp
    <div class="container">

        <div class="toptravel"></div>
        <div class="row">
            <div class="col-12 col-md-12" style="padding:  0px;">


                <img src="{{ $banner }}" class="img-fluid d-block w-100" >


            </div>

        </div>


        <div class="row">

            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="headtext top2rem">{{$blog->name_blog}}</div>
       
                    <div class="underheadtext" style="color: #212429;">
                        @php
                            $type_blog =  explode('|',$blog->type_blog);
                        @endphp
                        @foreach ($type_blog as $key => $item)

                            @php
                                // echo  $key;
                                    $cunt_type_blog = count($type_blog);
                                    $type_blog2 = App\Model\admin\TypeBlogModel::where('type_blogid','=',$item)->get();

                            @endphp
                            @foreach ($type_blog2 as $key2 => $typeblog)
                                <a href="{{ url('/tagtravel',$typeblog->type_blogid) }}" style="color:black;">{{ $typeblog->name_type }}</a>
                                @if (++$key != $cunt_type_blog)
                                    {{ '/' }}
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                
                <div class="travelnormaltext border-bottom">{{$blog->address_blog}}</div>
                {{--<div class="miniheadtext top2rem">{{$blog->name_blog}}</div>--}}
                <div class="normaltext top0rem">{{$blog->title_blog}}
                </div>
                <div class="normaltext top0rem insertImg">
                    
                        {!!$blog->detail_blog !!}
                    
                   
                </div>
                <script>
                    $(".insertImg img").each(function (){
                        $(this).removeAttr('style');
                        $(this).addClass('img-responsive col-md-12');
                        $(this).css('padding','0');
                    });

                </script>
                {{--<img src="{{ url('img/travelguidedetail/detail01.png') }}" class="img-fluid d-block w-100 top2rem">--}}
                {{--<div class="miniheadtext top2rem">บรรยากาศ</div>--}}
                {{--<div class="normaltext top0rem">ตั้งแต่เดินเข้าที่พักมาก็สัมผัสได้ถึงความสงบร่มเย็น--}}
                {{--และธรรมชาติที่รอเราเข้าไปซึมซับอยู่ ทางด้านหน้าของที่พักจะมีสวนดอกไม้ ร่มรื่นมากๆ--}}
                {{--เดินเข้าไปข้างในอีกหน่อย ก็จะเจอกับเจ้าเป็ดน้อย และสองห่านขาวคู่เกลอ ที่เดินคุมไปทั่วที่พักเลย--}}
                {{--น่าเอ็นดูมากๆ--}}
                {{--เขยิบเข้าไปอีกก็จะได้เห็น “ไฮไลท์” ของที่นี่เลย นั่นก็คือสะพานไม้ไผ่ทอดยาวไปตามบ้านพักและริมนำ้--}}
                {{--ทั้งหมดที่พูดมานี้--}}
                {{--เราจะเจอได้ที่ “บ้านกกกอด” จังหวัดกาญจนบุรี--}}
                {{--</div>--}}
                {{--<img src="{{ url('img/travelguidedetail/detail02.png') }}" class="img-fluid d-block w-100 top2rem">--}}
                {{--<div class="miniheadtext top2rem">อาหาร</div>--}}
                {{--<div class="normaltext top0rem">อาหารของที่นี่อาจจะดูง่ายๆธรรมดาๆ แต่กินแล้วนึกถึงบ้านเลย--}}
                {{--สุขใจเหมือนกลับบ้านไปพักผ่อนเลยล่ะ กินไปชมวิวไป มองไป ทางไหนก็สดชื่น เจอภูเขา เจอนำ้ เจอต้นไม้--}}
                {{--เจอดอกไม้ เปรมปรีย์สุดๆ--}}
                {{--</div>--}}
                <a data-fancybox="gallery" href="{{url('storage/blog/'.$blog->img_blog_index)}}">
                    <div class="controlimgboxtravelblog">
                        <img src="{!! ($blog->img_blog_index !=''? url('storage/blog/'.$blog->img_blog_index)  : url('img/travelguidedetail/img23.png')) !!}" class="img-fluid d-block w-100 top2rem">
                    </div>
                </a>
                
                <div class="row">
                    @foreach($file AS $rowFile)
                    <div class="col   "style="{{ ($noV == 1 ?'padding-right: 0;': (5 === $noV ?'padding-left: 0;':'padding: 0;')) }} {{ $noV > 5 ? 'display:none' : '' }}">
                            <a data-fancybox="gallery" href="{{url('storage/blog/'.$rowFile->name)}}">
                                <div class="controlimgboxtravelblog">
                                    <img class="imgbox" src="{{url('storage/blog/'.$rowFile->name)}}">
                                </div>
                            </a>
                        </div>
                        @php $noV++; @endphp
                    @endforeach
                    {{--<div class="col" style="padding-right: 0;">--}}
                    {{--<a data-fancybox="gallery" href="img/travelguidedetail/img23.png">--}}
                    {{--<div class="controlimgboxtravelblog">--}}
                    {{--<img class="imgbox" src="img/travelguidedetail/img23.png">--}}
                    {{--</div>--}}
                    {{--</a>--}}
                    {{--</div>--}}


                </div>

                <div class="border-bottom"></div>

                <div class="miniheadtext top2rem">ข้อมูลเพิ่มเติม</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="jrminiheadtext top1rem">ที่อยู่</div>
                        <div class="normaltext top0rem">{{$blog->address_blog}}</div>
                        <div class="jrminiheadtext top1rem">เวลาเปิดทำการ</div>
                        <div class="normaltext top0rem">{{$blog->time_work}}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="jrminiheadtext top1rem">ติดต่อ</div>
                        <div class="normaltext top0rem">{{$blog->tel_blog}}</div>
                        <div class="jrminiheadtext top1rem">ราคา</div>
                        <div class="normaltext top0rem">{{$blog->price_blog}}</div>
                    </div>
                </div>

                <div class="border-bottom"></div>
                <div id="share"></div>


            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">

                <div class="top1rem text-right facebooktop">
                    <div id="share2"></div>
                </div>
                @php
                    $featured = DB::table('featured_total')
                                  ->where('blog_feat','!=', $id)
                                  ->where('time_stamp','=', Session::get('view_last'))
                                  ->orderBy('created_at','DESC')
                             ->first();
                @endphp

                @if(@$featured->blog_feat != '')
                    @php
                        $feat = DB::table('tb_blog')
                        ->where('id_blog', '=', $featured->blog_feat)->first();
                    @endphp
                    <div class="miniheadtext">FEATURED IN</div>

                    <div class="col-sm-12 col-md-12 top1rem " style="padding:  0px;">
                        <a href=" ">
                            <img src="{!! ($feat->img_blog_index !=''? url('storage/blog/'.$feat->img_blog_index)  : url('img/travelguidedetail/img23.png')) !!}"
                                 class="img-fluid ">
                            <div class="minitraveltext top0rem" style="color: #707070;">{{ $feat->title_blog }}
                            </div>
                        </a>
                    </div>
                @endif
                @if(count($viewTotal) > 0)
                    <div class="miniheadtext top2rem">MOST POPULAR</div>
                    @foreach($viewTotal AS $view )
                        <div class="row top1rem">

                            <div class="col-md-6 col-6">
                                <a  href="{!! url('travelblogdetail',$view->id_blog) !!}">
                                    <img src="{!! ($view->img_blog_index !=''? url('storage/blog/'.$view->img_blog_index)  : url('img/travelguidedetail/img23.png')) !!}"
                                         class="img-fluid d-block w-100">
                                </a>
                            </div>

                            <div class="col-md-6 col-6">
                                <a href="{!! url('travelblogdetail',$view->id_blog) !!}">
                                    <div class="minitraveltext top1rem" style="color: #707070;">
                                        {{ $view->title_blog }}
                                    </div>
                                </a>
                            </div>

                        </div>
                    @endforeach
                @endif

                <a href=" ">
                    <img src="{{ url('img/travelguidedetail/ads.png') }}" class="img-fluid d-block w-100 top2rem">
                </a>
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
        $("#share").jsSocials({
            showCount: false,
            showLabel: false,
            shareIn: "popup",
            shares: ["facebook", "twitter"],
        });

         $("#share2").jsSocials({
            showCount: false,
            showLabel: false,
            shareIn: "popup",
            shares: ["facebook", "twitter"],
        });
    </script>

    <script>
        $( document ).ready(function() {
            
            // fbq('track', 'ViewContent', { content_ids: ['{{$blog->id_blog}}'], content_name: '{{$blog->name_blog}}', content_type: 'product' }) 
        });
        
    </script>
    
@endsection