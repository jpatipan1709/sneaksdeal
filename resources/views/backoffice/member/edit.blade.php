@extends("backoffice/layout/components")

@section('top1') Member @endsection

@section('top2') home @endsection

@section('top3') Member Edit @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "member";
?>

@section('contents')
    @php if($errors->has('name'))
                                    $name ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('last_name').'
                                     </span>';
    if($errors->has('last_name'))
                                    $last_name ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('last_name').'
                                     </span>';
    if($errors->has('tel'))
                                    $tel ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('tel').'
                                     </span>';
    if($errors->has('rePassword'))
                                    $rePassword ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('rePassword').'
                                     </span>';
    @endphp
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        แก้ไข ข้อมูลสมาชิก
                    </h4>
                </div>
                <form action="{{ url('backoffice/member',$data->id_member) }}" method="post" id="frmUpdate">
                    @csrf
                    {{ method_field('PATCH') }}
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
                        
                        <div class="row">
                            <div class="col-lg-6">
                                {!! inputText('ชื่อ', 'name', '', 'Name ...', 'lg-12', 'required',$data->name_member) !!}
                                {!! inputText('นามสกุล', 'last_name', 'id-input', 'Last name ...', 'lg-12', 'required',$data->lastname_member) !!}
                                {!! inputText('เบอร์โทร', 'tel', '', 'Tel ...', 'lg-12', 'required',$data->tel_member) !!}
                            </div>
                            <div class="col-lg-6">
                                {!! inputEmail('Email/Username', 'email', '', 'Email/Username ...', 'lg-12', 'required readonly',$data->email) !!}
                                {!! inputPassword('New Password', 'password', '', 'Password ...', 'lg-12', '','') !!}
                                {!! inputPassword('Confirm New Password'.@$rePassword, 'rePassword', '', 'Confirm Password ...', 'lg-12', '','') !!}
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
        //         url: "{!! URL("backoffice/member/".$data->id_member ) !!}",
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


