@extends("backoffice/layout/components")

@section('top1') Profile @endsection

@section('top2') home @endsection

@section('top3') Profile detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
    tbody {
        text-align: center;
    }
</style>
<?php
$active = "profile";

$img = Session::get('file_img_admin') != '' ? url('storage/admin/' . Session::get('file_img_admin')) : url('backoffice/dist/img/img-default.jpg');
$stat = $data->name_main != '' ? $data->name_main : $data->status_admin;
?>

@section('contents')
    <div class="row">
        <section class="col-lg-12 ">
            @if(\Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                    <li>{{ \Session::get('success') }}</li>
                </div><br/>
            @endif
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;"><h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"> </i> ข้อมูลส่วนตัว </h4>

                </div>
                <form name="frmCreate" id="frmUpdate" method="post" action="{{ url('backoffice/profile') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-lg-12" id="validatorHead" style="display: none;">
                            <div class="card bg-warning-gradient">
                                <div class="card-header">
                                    <h3 class="card-title headValidate">Validator</h3>
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
                            <div class="col-lg-12">
                                {!! uploadSingleImage($img,'Upload Profile','Size<br>160 x 160 ','lg-4
                                offset-lg-4','250px','200px') !!}
                            </div>
                            <div class="col-lg-6">
                                {!! inputText('สถานะบัญชี', 'stat', 'stat', ' ', 'lg-12', 'readonly',$stat) !!}
                                @php if($errors->has('name'))
                                    $name='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('name').'
                                     </span>';
                                @endphp
                                {!! inputText('ชื่อ'.@$name, 'name', 'name', 'Name ...', 'lg-12', '',$data->name_admin) !!}
                                @php if($errors->has('last_name'))
                                    $lastname ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('last_name').'
                                     </span>';
                                @endphp
                                {!! inputText('นามสกุล'.@$lastname, 'last_name', 'last_name', 'Last name ...', 'lg-12',
                                '',$data->lastname_admin) !!}
                            </div>
                            <div class="col-lg-6">
                                {!! inputEmail('Email/Username', 'email', 'email', 'Email/Username ...', 'lg-12',
                                'readonly',$data->email) !!}
                                {!! inputPassword('New Password', 'password', 'password', 'Password ...', 'lg-12', '','')
                                !!}
                                @php if($errors->has('rePassword'))
                                    $password ='<span class="text-danger text-size-error" role="alert">
                                    '. $errors->first('rePassword').'
                                     </span>';
                                @endphp
                                {!! inputPassword('Confirm New Password'.@$password, 'rePassword', 'rePassword', 'Confirm Password
                                ...', 'lg-12', '','') !!}
                                <input type="hidden" name="id" value="{{ $id }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit"
                                class="btn btn-info float-right"><i class="far fa-edit"></i> Save
                            Edit
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <div id="resultUpdate"></div>

@endsection


@section('script')
    <script>

        function submit(idForm) {
            $.ajax({
                url: "{!! URL("backoffice/profile") !!}",
                type: "POST",
                headers:
                    {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                ,
                data: new FormData($('#' + idForm + '')[0]),
                contentType: false,
                cache: false,
                processData: false,
                success:
                    function (data) {
                        if (data.errors) {
                            jQuery.each(data.errors, function (key, value) {
                                $('#validatorHead').show();
                                $('#validator').append("<li >" + value + "</li>")
                            });
                        } else {
                            $('#resultUpdate').html(data);
                            $('#validatorHead').hide();
                        }
                    }
            })
            ;
        }
    </script>
@endsection