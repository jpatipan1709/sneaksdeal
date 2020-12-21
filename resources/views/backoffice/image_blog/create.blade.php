@extends("backoffice/layout/components")

@section('top1') Image Blog  @endsection

@section('top2') home @endsection

@section('top3') Image Blog  @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "image_blog";


?>

@section('contents')

    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        เพิ่มรูป Ckeditor Blog
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{ url('backoffice/image_blog') }}" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-arrow-left"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" action="{{ url('backoffice/image_blog') }}" id="frmCreate" method="post" enctype="multipart/form-data">
                    @csrf
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">
                        {!! uploadSingleImage(url('backoffice/dist/img/img-default.jpg'),'Upload Icon','Size<br>1620 x 1080 ','lg-4 offset-lg-4','100%','200px') !!}
                        <input type="hidden" name="type" value="blog">
                        <div class="row">
                            {!! inputSelect2('เลือกอัลบั้ม', 'album', '', '', 'md-12', 'required', $option) !!}
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
    {{--<script>--}}
    {{--alertify.error('sss');--}}
    {{--</script>--}}
    <div id="resultCreate"></div>

@endsection

@section('script')

    <script>

        function submit() {
            $.ajax({
                url: "../image_blog",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: new FormData($('#frmCreate')[0]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#resultCreate').html(data);
                }
            });
        }


    </script>

@endsection
