@extends("backoffice/layout/components")

@section('top1') Banner Single Journey @endsection

@section('top2') home @endsection

@section('top3') Banner detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "banner_single";


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
                        เพิ่มรูปแบบเนอร์
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{url('backoffice/banner_single_journey')}}"
                      enctype="multipart/form-data">
                    @csrf
                    {{--<input type="hidden" name="_token" value="{{csrf_token()}}">--}}
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">
                        @php if($errors->has('fileToUpload'))
                                    $fileTo ='<br> <center><span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('fileToUpload').'
                                     </span> </center>';
                        @endphp
                        {!! uploadSingleImage(url('backoffice/dist/img/img-default.jpg'),'Upload Icon','Size<br>1920 x 640 ','lg-4 offset-lg-4','100%','200px') !!}
                        {!!  @$fileTo !!}

                        {!! inputText('link แบนเนอร์', 'link', 'id-input', 'Enter ...', 'lg-12', '','') !!}
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
        $(function () {
            $('#fileUpload').attr('required', true);
        })
        // function submit() {
        //     $.ajax({
        //         url: "../banner",
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
