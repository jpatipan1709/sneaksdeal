@extends("backoffice/layout/components")

@section('top1') Blog @endsection

@section('top2') home @endsection

@section('top3') Blog detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "blog";


?>
@php if($errors->has('type_blog'))
                                    $type_blog ='<br><span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('type_blog').'
                                     </span>';
@endphp
@section('contents')

<div class="row">
    <!-- Left col -->
    <section class="col-lg-12 ">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h4 class="pull-left">
                    <i class="ion ion-clipboard mr-1"></i>
                    เพิ่มข้อมูล Blog
                </h4>

                <div class="pull-right" style="float: right">
                    <a href="{{url('backoffice/blog')}}" class="btn btn-flat btn-primary"><i
                                class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                </div>
            </div>


            <!-- /.card-header -->
            <form name="frmCreate" id="frmCreate" method="post" action="{{url('backoffice/blog')}}"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="row">
                        {!! uploadSingleImage(url('backoffice/dist/img/img-default.jpg'),'Banner blog','Size<br>1,920 x
                        640 ',' lg-6 ','400px','200px') !!}

                        {!! uploadMultipleImage(url('backoffice/dist/img/img-default.jpg'),'Album Blog','Size<br>1,620 x
                        1080 ','lg-6 ','400px','200px') !!}
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <label>ประเภท Blog</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    @foreach ($data as $value)
                                    {!! inputCheckbox( $value->name_type, 'type_blog[]', '', '', $value->type_blogid)
                                    !!}
                                    @endforeach
                                    {!! @$type_blog !!}

                                </div>
                            </div>
                            {!! inputText('ชื่อ Blog', 'name_blog', 'id-input', 'Enter ...', 'lg-12', 'required',old('name_blog')) !!}
                            {!! inputText('เวลาทำการ', 'time_open', 'id-input', 'Enter ...', 'lg-12', 'required',old('time_open')) !!}
                            {!! inputTextArea('ที่อยู่', 'address_blog', 'id-input', '', 'lg-12', 'required',old('address_blog')) !!}


                        </div>
                        <div class="col-lg-6">
                            {!! inputText('เบอร์โทร', 'tel', 'id-input', 'Enter ...', 'lg-12', 'required',old('tel')) !!}
                            {!! inputText('ราคาประมาณ', 'price', 'id-input', 'Enter ...', 'lg-12', 'required',old('price')) !!}
                            {!! inputTextArea('title', 'title', 'id-input', '', 'lg-12', 'required',old('title')) !!}

                        </div>
                        <div class="col-lg-12">
                            {!! inputTextArea('รายละเอียด <a style="color:red" target="_blank" href="/backoffice/image_blog">อัลบั้มรูปภาพคลิก</a>', 'detail', 'id-input', 'ckeditor', 'lg-12', 'required',old('detail'))
                            !!}

                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <button type="submit" class="btn btn-info float-right"><i class="fa fa-plus"></i> Add
                        item
                    </button>
                </div>
            </form>
        </div>


        <!-- /.card -->
    </section>
    <!-- right col -->
</div>
{{--
<script>--
    }
    }
    {
        {
            --alertify.error('sss');
            --
        }
    }
    {
        {
            --</script>--}}
<div id="resultCreate"></div>

@endsection

@section('script')
<script>

    // function submit() {
    //     for ( instance in CKEDITOR.instances ) { CKEDITOR.instances[instance].updateElement();}
    //     $.ajax({
    //         url: "../blog",
    //         type: "POST",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         data: new FormData($('#frmCreate')[0]),
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function (data) {
    //             $('#resultCreate').html(data);
    //         }
    //     });
    // }


</script>

@endsection
