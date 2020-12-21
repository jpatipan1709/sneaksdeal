@extends("backoffice/layout/components")

@section('top1') Admin @endsection

@section('top2') home @endsection

@section('top3') Admin Edit @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
$active = "admin";
$option = '<option '.($data->main_id_at == 0 ? 'selected' :'').' value="0">User : Sneaksdeal</option>';
foreach ($main AS $val){
$option .= '<option value="'.$val->id_main.'" '.($data->main_id_at == $val->id_main ? 'selected' :'').'>User : '.$val->name_main.'</option>';
}
@endphp

@php
if($errors->has('name'))
$name ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('name').'
                                     </span>';
if($errors->has('last_name'))
$lastname ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('last_name').'
                                     </span>';

if($errors->has('rePassword'))
$rePassword ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('rePassword').'
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
                        แก้ไข ข้อมูล
                    </h4>
                </div>
                <form action="{{url('backoffice/admin',$data->id_admin ) }}" method="post" id="frmUpdate" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-lg-12" id="validatorHead" style="display: none;">
                            <div class="card bg-warning-gradient">
                                <div class="card-header">
                                    <h3 class="card-title headValidate" >ISSUE</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <div class="card-body" id="validator">
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        {{ method_field('PATCH') }}
                        <div class="row">
                            <div class="col-lg-12">
                                {!! uploadSingleImage(url('storage/admin/'.$data->file_img),'Upload Profile','Size<br>160 x 160 ','lg-4 offset-lg-4','250px','200px') !!}
                            </div>
                            <div class="col-lg-6">
                                {!! inputSelect2('สถานะบัญชีผู้ใช้', 'stat_admin', 'stat_admin', '', 'lg-12', '', $option) !!}
                                {!! inputText('ชื่อ'.@$name, 'name', 'id-input', 'Name ...', 'lg-12', 'required',$data->name_admin) !!}
                                {!! inputText('นามสกุล'.@$last_name, 'last_name', 'id-input', 'Last name ...', 'lg-12', 'required',$data->lastname_admin) !!}
                            </div>
                            <div class="col-lg-6">
                                {!! inputEmail('Email/Username', 'email', 'id-input', 'Email/Username ...', 'lg-12', 'readonly',$data->email) !!}
                                {!! inputPassword('Password', 'password', 'id-input', 'Password ...', 'lg-12', '','') !!}
                                {!! inputPassword('ConfirmPassword'.@$rePassword, 'rePassword', 'id-input', 'Confirm Password ...', 'lg-12', '','') !!}

                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit"  class="btn btn-info float-right"><i class="far fa-edit"></i> Save
                            Edit
                        </button>
                    </div>
                </form>

            </div>
            <div id="resultUpdate"></div>
            <!-- /.card -->
        </section>

        <!-- right col -->
    </div>
@endsection
@section('script')
    <script>

        // function submit(idForm) {
        //     $.ajax({
        //         url: "{!! URL("backoffice/admin/".$data->id_admin ) !!}",
        //         type: "POST",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: new FormData($('#' + idForm + '')[0]), _method: "PATCH",
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         success: function (data) {
        //             if(data.errors) {
        //                 jQuery.each(data.errors, function (key, value) {
        //                     $('#validatorHead').show();
        //                     $('#validator').append("<li >" + value + "</li>")
        //                 });
        //             }else{
        //                 $('#resultUpdate').html(data);
        //                 $('#validatorHead').hide();
        //             }
        //         }
        //     });
        // }
    </script>

@endsection


