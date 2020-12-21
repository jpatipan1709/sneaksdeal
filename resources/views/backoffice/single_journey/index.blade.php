@extends("backoffice/layout/components")

@section('top1') Single Journey @endsection

@section('top2') home @endsection

@section('top3') Single Journey @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "single_journey";
?>

@section('contents')
    <style>textarea {
            min-height: 200px;
        }</style>
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
                @if(\Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                        <li>{{ \Session::get('success') }}</li>
                    </div><br />
                @endif
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        อัพเดทข้อมูลเส้นทางคนโสด
                    </h4>
                </div>
                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{url('backoffice/single_journey')}}"
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
                        {!! uploadSingleImage(url('storage/single_journey',$data->file_name),'Upload Icon','Size<br>1920 x 640 ','lg-4 offset-lg-4','100%','200px') !!}
                        {!!  @$fileTo !!}

                        {!! inputTextArea('รายอะเลียด', 'detail', 'detail', 'Enter ...', 'lg-12', 'required',$data->detail_name) !!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-info float-right"><i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </form>
                <!-- /.card-body -->
                <div class="card-footer clearfix">

                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>

@endsection


@section('script')
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <div id="resultDelete"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">

    <script>

    </script>
@endsection