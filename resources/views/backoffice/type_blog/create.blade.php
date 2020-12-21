@extends("backoffice/layout/components")

@section('top1') Tag @endsection

@section('top2') home @endsection

@section('top3') Tag detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "type_blog";
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
                        กรุณากรอกรายละเอียด Tag
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{ url('backoffice/type_blog') }}"  class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" action="{{ url('backoffice/type_blog') }}" id="frmCreate" method="post"enctype="multipart/form-data">
                    @csrf
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">
                        {!! inputText('ชื่อ Tag', 'name_type', 'id-input', 'Enter ...', 'lg-12', 'required','') !!}

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
                url: "../type_blog",
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
