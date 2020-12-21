@extends("backoffice/layout/components")

@section('top1') Facilities @endsection

@section('top2') home @endsection

@section('top3') Facilities detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "facilities";
$img = $data->icon_facilities != '' ? url('storage/facilities/' . $data->icon_facilities) : url('backoffice/dist/img/img-default.jpg');

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
                    แก้ไขข้อมูล สิ่งอำนวยความสะดวก
                </h4>

                <div class="pull-right" style="float: right">
                    <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                </div>
            </div>


            <!-- /.card-header -->
            <form name="frmUpdate" id="frmUpdate" method="post" action="{{url('backoffice/facilities'),$data->id_facilities}}"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                {{ method_field('PATCH') }}
                <div class="card-body">
                    {!! uploadSingleImage($img,'Upload Icon','Size<br>23 x 23 ','lg-4 offset-lg-4','250px','200px') !!}
                    {!! inputText('ชื่อสิ่งอำนวยความสะดวก', 'nameFacilities', 'id-input', 'Enter ...', 'lg-12',
                    'required',$data->name_facilities) !!}

                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <!--                        <a href="#" onclick="submit()" class="btn btn-info float-right"><i class="far fa-edit"></i> Save Edit-->
                    <!--                        </a>-->
                    <button type="submit"  class="btn btn-info float-right"><i class="far fa-edit"></i> Save
                        Edit
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
<div id="resultUpdate"></div>

@endsection

@section('script')

<script>

    // function submit() {
    //     $.ajax({
    //         url: "../../facilities/{{$data->id_facilities}}",
    //         type: "POST",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         data: new FormData($('#frmUpdate')[0]),_method: "PATCH",
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function (data) {
    //             $('#resultUpdate').html(data);
    //         }
    //     });
    // }


</script>

@endsection
