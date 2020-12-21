@extends("backoffice/layout/components")

@section('top1') Blog @endsection

@section('top2') home @endsection

@section('top3') Blog detail @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
$active = "blog";
$img = $data->banner_blog != '' ? url('storage/blog/' . $data->banner_blog) : url('backoffice/dist/img/img-default.jpg');
$img2 = $data->img_blog_index != '' ? url('storage/blog/' . $data->img_blog_index) : url('backoffice/dist/img/img-default.jpg');
$typeData = explode('|', $data->type_blog);

@endphp
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
                    แก้ไขข้อมูล Blog
                </h4>

                <div class="pull-right" style="float: right">
                    <a href="{{url('backoffice/blog')}}"  class="btn btn-flat btn-primary"><i
                                class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                </div>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active show" href="#tabFormUpdate"
                                            data-toggle="tab">ฟอร์มแก้ไขข้อมูล</a></li>
                    <li class="nav-item"><a class="nav-link" href="#album" data-toggle="tab">จัดการอัลบัมรูปภาพ</a>
                    </li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="tab-content">
                <div class="tab-pane active show" id="tabFormUpdate">
                    <form name="frmUpdate" id="frmUpdate" enctype="multipart/form-data" method="post"
                          action="{!! url("backoffice/blog",$data->id_blog ) !!}" >
                    @csrf
                    {{ method_field('PATCH') }}
                    <div class="card-body">


                        <div class="row">
                            {!! uploadSingleImage($img,'Banner blog','Size<br>1,920 x 640 ',' lg-6 ','400px','200px')
                            !!}
                            {!! uploadMultipleImage($img2,'Album Blog','Size<br>1,620 x 1080 ','lg-6 ','400px','200px')
                            !!}
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label>ประเภท Blog</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        @foreach ($type AS $value)

                                        {!! inputCheckbox( $value->name_type, 'type_blog[]', '',
                                        (array_search($value->type_blogid ,$typeData) > -1 ?'checked':''),
                                        $value->type_blogid) !!}
                                        @endforeach
                                            {!! @$type_blog !!}

                                    </div>
                                </div>
                                {!! inputText('ชื่อ Blog', 'name_blog', 'id-input', 'Enter ...', 'lg-12',
                                'required',$data->name_blog) !!}
                                {!! inputText('เวลาทำการ', 'time_open', 'id-input', 'Enter ...', 'lg-12',
                                'required',$data->time_work) !!}
                                {!! inputTextArea('ที่อยู่', 'address_blog', 'id-input', '', 'lg-12',
                                'required',$data->address_blog) !!}


                            </div>
                            <div class="col-lg-6">
                                {!! inputText('เบอร์โทร', 'tel', 'id-input', 'Enter ...', 'lg-12',
                                'required',$data->tel_blog) !!}
                                {!! inputText('ราคาประมาณ', 'price', 'id-input', 'Enter ...', 'lg-12',
                                'required',$data->price_blog) !!}
                                {!! inputTextArea('title', 'title', 'id-input', '', 'lg-12',
                                'required',$data->title_blog) !!}
                            </div>
                            <div class="col-lg-12">
                                {!! inputTextArea('รายละเอียด <a style="color:red" target="_blank" href="/backoffice/image_blog">อัลบั้มรูปภาพคลิก</a>', 'detail', 'id-input', 'ckeditor', 'lg-12',
                                'required',$data->detail_blog) !!}

                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-info float-right"><i
                                    class="far fa-edit"></i>
                            Save
                            item
                        </button>
                    </div>
                    </form>
                </div>

                {{--tab2--}}
                <div class="tab-pane" id="album">
                    <form name="frmAlbum" id="frmAlbum" method="post" action="{!! url("backoffice/blog",$data->id_blog ) !!}"
                          enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PATCH') }}

                        <input type="hidden" name="id_blog" value="{{$data->id_blog}}">
                        <div class="card-body">
                            <div class="row" id="sortable">
                                @php $i = 1; @endphp
                                @foreach($album AS $row)
                                <div class="col-lg-3" style="border-color: grey;border-style: solid">
                                    <label>No. {{ $i }}</label><input type="hidden" name="imgSort[]"
                                                                      value="{{ $row->id }}">
                                    <img src="{{ url('storage/blog/'.$row->name) }}" class="imgAlbum" style="width: 100%;height: 200px;
        padding: 5px;"><br>

                                    {!! inputSetImage('ตั้งเป็น รูปภาพหลัก', 'setImage', '', ($data->img_blog_index ==
                                    $row->name?'checked':'' ), $row->id) !!}

                                </div>

                                @php $i++; @endphp
                                @endforeach

                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="#" style="display: block;" onclick="getValues()"
                               class="btn btn-warning float-left"><i
                                        class="far fa-edit"></i>
                                Save
                                Sort

                            </a>
                            <a style="display: block;margin-left: 5px" onclick="reloadAlbum()"
                               class="btn btn-warning float-left"><i class="fas fa-sync-alt"></i>
                                Refresh
                                Page

                            </a>
                            <a href="#" id="btnSetImage" style="display: none;" onclick="submit('frmAlbum')"
                               class="btn btn-info float-right"><i
                                        class="far fa-edit"></i>
                                Save
                                item
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div id="resultUpdate"></div>
        <div id="image">
        </div>

        <!-- /.card -->
    </section>
    <!-- right col -->
</div>

<form id="formDeleteImage" name="formDeleteImage" method="post" enctype="multipart/form-data" action="{!! url("backoffice/blog",$data->id_blog ) !!}">
@csrf
{{ method_field('PATCH') }}
<input type="hidden" id="deleteImage" name="deleteImage">
</form>
@endsection

@section('script')

<script>
    $('input[name="setImage"]').click(function () {
        $('#btnSetImage').show();
    });

    function del(value) {
        alertify.confirm("ลบข้อมูล", "คุณต้องการลบรูปภาพนี้",
            function () {
                $(function () {
                    $('#deleteImage').val(value);
                    submit('formDeleteImage');
                    alertify.success('success');
                });
            },
            function () {
                alertify.error('cancel');
            });

    }

    function submit(idForm) {
        $('#' + idForm + '').submit();
    }


    function reloadAlbum() {
        window.location.reload();

    }

</script>

<script>
    $(function () {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
    });
    window.getValues = function () {
        $.ajax({
            url: '{{ url('backoffice/sort/album') }}',
            data:
        $('#frmAlbum').serialize(), _method:"PATCH", type:'POST',
        success:function (data) {
            $('#image').html(data);
            // $('#album').load(" #album");

        }
    })
        ;
    }
</script>
@endsection
