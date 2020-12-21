@extends("backoffice/layout/components")

@section('top1') Facilities @endsection

@section('top2') home @endsection

@section('top3') Facilities detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "facilities";


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
                        กรุณากรอกรายละเอียด สิ่งอำนวยความสะดวก
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{ url('backoffice/facilities') }}" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{ url('/backoffice/facilities') }}"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">
                        {!! uploadSingleImage(url('backoffice/dist/img/img-default.jpg'),'Upload Icon','Size<br>23 x 23 ','lg-4 offset-lg-4','250px','200px') !!}
                      <div class="text-center">
                      @if ($errors->has('fileToUpload'))
                            <span class="text-danger text-size-error" role="alert">
                                {{ $errors->first('fileToUpload') }}
                            </span>
                        @endif
                      </div>
                        {!! inputText('ชื่อสิ่งอำนวยความสะดวก', 'nameFacilities', 'id-input', 'Enter ...', 'lg-12', 'required','') !!}

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit"  class="btn btn-info float-right"><i class="fa fa-plus"></i> Add 
                        item
                        </button>
                        {{-- <a href="javascript:" onclick="submit()" class="btn btn-info float-right"><i
                                    class="fa fa-plus"></i> Add
                            item
                        </a> --}}
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

        // function submit() {

        //     $.ajax({
        //         url: "{{url('/backoffice/addfac')}}",
        //         type: "POST",
        //         // headers: {
        //         //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         // },
        //         headers: {
        //             "cache-control": "no-cache"
        //         },
        //         data: new FormData($('#frmCreate')[0]),
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         async: true,
        //         success: function (data) {
        //             $('#resultCreate').html(data);
        //         }
        //     });

        // }


    </script>

@endsection
