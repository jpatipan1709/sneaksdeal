@extends("backoffice/layout/components")
@section('top1') Member
@endsection
@section('top2') home @endsection
@section('top3') Member detail @endsection
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
    if($errors->has('email'))
                                    $email ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('email').'
                                     </span>';
    if($errors->has('password'))
                                    $password ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('password').'
                                     </span>';
    if($errors->has('rePassword'))
                                    $rePassword ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('rePassword').'
                                     </span>';
    @endphp
    <div class="row">
        <section class="col-lg-12 ">
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;"><h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"> </i> กรุณากรอกรายละเอียด สมาชิก </h4>
                    <div class="pull-right" style="float: right"><a href="#" onclick="go_back()"
                                                                    class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a></div>
                </div>
                <form name="frmCreate" action="{{ url('backoffice/member') }}" id="frmCreate" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-lg-12" id="validatorHead" style="display: none;">
                            <div class="card bg-warning-gradient">
                                <div class="card-header">
                                    <h3 class="card-title headValidate">ISSUE</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
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

                                {!! inputText('ชื่อ'.@$name, 'name', 'id-input', 'Name ...', 'lg-12', 'required',old('name')) !!}
                                {!! inputText('นามสกุล'.@$last_name, 'last_name', 'id-input', 'Last name ...', 'lg-12', 'required',old('last_name')) !!}
                                {!! inputText('เบอร์โทร'.@$tel, 'tel', 'id-input', 'Tel ...', 'lg-12', 'required',old('tel')) !!}
                            </div>
                            <div class="col-lg-6">
                                {!! inputEmail('Email/Username'.@$email, 'email', 'id-input', 'Email/Username ...', 'lg-12', 'required',old('email')) !!}
                                {!! inputPassword('Password'.@$password, 'password', 'id-input', 'Password ...', 'lg-12', 'required',old('password')) !!}
                                {!! inputPassword('ConfirmPassword'.@$rePassword, 'rePassword', 'id-input', 'Confirm Password ...', 'lg-12', 'required',old('rePassword')) !!}

                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" id="checkAdd"
                                class="btn btn-info float-right"><i
                                    class="fa fa-plus"></i> Add item
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <div id="resultCreate"></div>
@endsection

@section('script')
    <script>
        // $("form").on("submit", function () {
        //     $.ajax({
        //         url: "{{url('backoffice/member')}}",
        //         type: "POST",
        //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //         data: new FormData($('#frmCreate')[0]),
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
        //                 $('#resultCreate').html(data);
        //                 $('#validatorHead').hide();

        //             }
        //         }
        //     });
        //     return false;
        // });


    </script>

@endsection