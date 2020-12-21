@extends("backoffice/layout/components")

@section('top1') Location @endsection

@section('top2') home @endsection

@section('top3') Location detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "location";


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
                        กรุณากรอกรายละเอียด Location
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{ url('backoffice/location') }}" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{ url('/backoffice/location') }}"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">

                        {!! inputText('name', 'name', 'id-input', 'Enter ...', 'lg-12', 'required','') !!}

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



    </script>

@endsection
