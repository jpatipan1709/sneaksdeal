@extends("backoffice/layout/components")

@section('top1')   Form Join Us @endsection

@section('top2') home @endsection

@section('top3')  Form Join Us detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "joinus";


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
                        Form Join Us
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{url('/backoffice/savejoinus')}}" enctype="multipart/form-data">
                    @csrf
                    {{--<input type="hidden" name="_token" value="{{csrf_token()}}">--}}
                    {{--{{ method_field('PATCH') }}--}}
                    <div class="card-body">
                            @if(\Session::has('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                                <li>{{ \Session::get('success') }}</li>
                            </div><br />
                        @endif
                        <div class="row">
                            
                            @php
                                $joinus_content = DB::table('joinus_content')->first();
                                if($joinus_content->juc_images1 == null){
                                    $condition_juc_images1 = url('backoffice/dist/img/img-default.jpg');
                                }else{
                                    $condition_juc_images1 = url('storage/joinus/'. $joinus_content->juc_images1);
                                }

                                if($joinus_content->juc_images2 == null){
                                    $condition_juc_images2 = url('backoffice/dist/img/img-default.jpg');
                                }else{
                                    $condition_juc_images2 = url('storage/joinus/'. $joinus_content->juc_images2);
                                }

                              
                                if($joinus_content->juc_text1 == null){
                                    $juc_text1 = old('text1');
                                }else{
                                    $juc_text1 = $joinus_content->juc_text1;
                                }

                                if($joinus_content->juc_text2 == null){
                                    $juc_text2 = old('text2');
                                }else{
                                    $juc_text2 = $joinus_content->juc_text2;
                                }

                                if($joinus_content->juc_content == null){
                                    $juc_content = old('detail');
                                }else{
                                    $juc_content = $joinus_content->juc_content;
                                }

                                if($joinus_content->juc_mail1 == null){
                                    $juc_mail1 = old('mail');
                                }else{
                                    $juc_mail1 = $joinus_content->juc_mail1;
                                }

                                if($joinus_content->juc_tel1 == null){
                                    $juc_tel1 = old('phone');
                                }else{
                                    $juc_tel1 = $joinus_content->juc_tel1;
                                }

                                if($joinus_content->juc_contact == null){
                                    $juc_contact = old('contact_me');
                                }else{
                                    $juc_contact = $joinus_content->juc_contact;
                                }

                                if($joinus_content->juc_address == null){
                                    $juc_address = old('address');
                                }else{
                                    $juc_address = $joinus_content->juc_address;
                                }

                                if($joinus_content->juc_tel2 == null){
                                    $juc_tel2 = old('phone2');
                                }else{
                                    $juc_tel2 = $joinus_content->juc_tel2;
                                }

                                if($joinus_content->juc_mail2 == null){
                                    $juc_mail2 = old('mail2');
                                }else{
                                    $juc_mail2 = $joinus_content->juc_mail2;
                                }

                                if($joinus_content->juc_map == null){
                                    $juc_map = old('juc_map');
                                }else{
                                    $juc_map = $joinus_content->juc_map;
                                }
                            @endphp
                            <div class="col-md-6">
                                    {!! uploadSingleImage($condition_juc_images1,'No.1','Size<br>537 x 350 ',' lg-6 ','400px','200px') !!}
                                    {!! inputText('Text 1: ', 'text1', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_text1) !!}
                            </div>
                            <div class="col-md-6">
                                    {!! uploadSingleImage2($condition_juc_images2,'Pic No.2','Size<br>537 x 350 ',' lg-6 ','400px','200px') !!}
                                    {!! inputText('Text 2: ', 'text2', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_text2) !!}
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    {!! inputTextArea('ร่วมงานกับเรา', 'detail', 'id-input', 'ckeditor', 'lg-12','required', $juc_content) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! inputText('Email: ', 'mail', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_mail1) !!}
                                {!! inputText('Phone: ', 'phone', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_tel1) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                 {!! inputTextArea('ติดต่อเรา', 'contact_me', 'id-input', 'ckeditor', 'lg-12','required', $juc_contact) !!}
                            </div>
                        </div>
                        <div class="row">
                               <div class="col-md-12">
                                    {!! inputText('ที่อยู่: ', 'address', 'id-input', 'Enter ...', 'lg-12', 'required', $juc_address) !!}
                                    {!! inputText('โทร : ', 'phone2', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_tel2) !!}
                                    {!! inputText('อีเมล  : ', 'mail2', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_mail2) !!}
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-12">
                                    {!! inputTextArea('Map', 'map', 'id-input', '', 'lg-12','required', $juc_map) !!}
                                     {{-- {!! inputText('Map  : ', 'map', 'id-input', 'Enter ...', 'lg-12', 'required',$juc_map) !!} --}}
                             </div>
                         </div>
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
