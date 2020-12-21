@extends("backoffice/layout/components")

@section('top1') Main Voucher @endsection

@section('top2') home @endsection

@section('top3') Main Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
    $active = "main_voucher";


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
                        แก้ไขข้อมูล Main Voucher
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{ url('backoffice/main_voucher') }}"  class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active show" href="#tabFormUpdate"
                                                data-toggle="tab">ฟอร์มแก้ไขข้อมูล</a></li>
                        <li class="nav-item"><a class="nav-link" href="#album" data-toggle="tab">จัดการอัลบัมรูปภาพ</a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-header -->
                <div class="tab-content">
                    <div class="tab-pane active show" id="tabFormUpdate">
                        <form name="frmUpdate" id="frmUpdate" method="post" onsubmit="return checkType()" action="{{ url('backoffice/main_voucher',$data->id_main ) }}" enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PATCH') }}
                            <div class="card-body">


                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>ข้อมูล Main Voucher</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @php  $textSpan = ' <span style="color:red"> (ถ้าไม่มีเว้นว่าง)</span>';
                                        @endphp
                                        {!! uploadMultipleImage(url('backoffice/dist/img/img-default.jpg'),'Album Main','Size<br>1,620 x 1080 ','lg-6 ','400px','200px') !!}

                                        {!! inputText('ชื่อ Main', 'name_main', 'id-input', 'Enter ...', 'lg-12', 'required',$data->name_main) !!}
                                        {!! inputText('ราคาประมาณ', 'price_main', 'id-input', 'Enter ...', 'lg-12', 'required',$data->price_main) !!}
                                        {!! inputText('link'.$textSpan, 'link', 'id-input', 'Enter ...', 'lg-12', '',$data->link_main) !!}


                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label>ประเภทดีล <span style="color: red;display: none"  id="txtDeal">*กรุณาเลือกประเภท</span></label>
                                            @if ($errors->has('type_voucher'))
                                                <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('type_voucher') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                @foreach ($typeVoucher as $value)
                                                    {!! inputCheckbox( $value->name_type.'('.$value->code_type.')', 'type_voucher[]', '', (@in_array($value->code_type,@explode(',',$data->code_type))?'checked':'') .' data-show="'.$value->type_show.'"', $value->code_type) !!}
                                                @endforeach

                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <label>แสดง VDO ก่อน</label><br>
                                            <label class="switch">
                                                <input type="checkbox" name="stat_show" value="y" {!! ($data->stat_show == 'y' ?'checked':'') !!} id="switch">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        {!! inputText('เบอร์โทร', 'tel_main', 'id-input', 'Enter ...', 'lg-12', 'required',$data->tel_main) !!}
                                        {!! inputTextArea('ที่อยู่', 'address_main', 'id-input', 'Enter ...', 'lg-12', 'required',$data->address_main) !!}
                                        {!! inputText('เวลาทำการ', 'time_main', 'id-input', 'Enter ...', 'lg-12', 'required',$data->time_main) !!}
                                        {!! inputTextArea('link VDO', 'link_vdo', 'id-input', '', 'lg-12', '',$data->link_vdo) !!}

                                    </div>
                                    <div class="col-lg-12">
                                        {!! inputTextArea('รายละเอียด'.$textSpan, 'detail_main', 'id-input', 'ckeditor', 'lg-12', '',$data->detail_main) !!}

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                             <div class="card-footer clearfix">
                                <button type="submit"  class="btn btn-info float-right"><i
                                            class="far fa-edit"></i>
                                    Save
                                    item
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="album">
                        <form name="frmAlbum" id="frmAlbum" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="id_main" value="{{$data->id_main}}">
                            {{ method_field('PATCH') }}
                            <div class="card-body">
                                <div class="row" id="sortable">
                                    @php $i = 1; @endphp
                                    @foreach($album AS $row)
                                        <div class="col-lg-3" style="border-color: grey;border-style: solid">
                                            <label>No. {{ $i }}</label><input type="hidden" name="imgSort[]"
                                                                              value="{{ $row->id }}">
                                            <img src="{{ url('storage/main/'.$row->name) }}" class="imgAlbum" style="width: 100%;height: 200px;
        padding: 5px;"><br>
                                            {!! inputSetImage('', 'setImage', '', '', $row->id) !!}


                                        </div>

                                        @php $i++; @endphp
                                    @endforeach

                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <a href="#" style="display: block;" onclick="getValues()"
                                   class="btn btn-warning float-left"><i
                                            class="far fa-edit"></i>
                                    Save
                                    Sort

                                </a>
                                <a style="display: block;margin-left: 5px" onclick="reloadAlbum()"
                                   class="btn btn-warning float-left"><i class="fas fa-sync-alt"></i>
                                    Refresh
                                    Page

                                </a>
                                {{--<a href="#" id="btnSetImage" style="display: none;" onclick="submit('frmAlbum')"--}}
                                   {{--class="btn btn-info float-right"><i--}}
                                            {{--class="far fa-edit"></i>--}}
                                    {{--Save--}}
                                    {{--item--}}
                                {{--</a>--}}
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div id="resultUpdate"></div>
            <div id="image">
            </div>

            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>

    <form id="formDeleteImage" name="formDeleteImage" method="post" enctype="multipart/form-data" action="{{ url('backoffice/main_voucher',$data->id_main ) }}">
     @csrf
        {{ method_field('PATCH') }}
        <input type="hidden" id="deleteImage" name="deleteImage">
    </form>

@endsection

@section('script')

    <script>

        function checkType() {
            var i = 0;
            $('input[name="type_voucher[]"]').each(function () {
                if($(this).is(":checked")){
                    i = 1;
                }
            });
            if(i == 0){
                $('#txtDeal').show();
                return false;
            }else{
                $('#txtDeal').hide();
                return true;

            }
        }
        $('input[name="type_voucher[]"]').click(function () {
            $('#txtDeal').hide();
            var show = $(this).data("show");
            if (show == 'single') {
                $('input[name="type_voucher[]"]').prop("checked",false);
                $(this).prop("checked",true);
            }

        });
        $('input[name="setImage"]').click(function () {
            $('#btnSetImage').show();
        });

        function del(value) {
            alertify.confirm("ลบข้อมูล", "คุณต้องการลบรูปภาพนี้",
                function () {
                    $(function () {
                        $('#deleteImage').val(value);
                        $('#formDeleteImage').submit();
                        alertify.success('success');
                    });
                },
                function () {
                    alertify.error('cancel');
                });

        }

        // function submit(idForm) {
        //     for ( instance in CKEDITOR.instances ) { CKEDITOR.instances[instance].updateElement();}
        //     $.ajax({
        //         url: "{!! URL("backoffice/main_voucher/".$data->id_main ) !!}",
        //         type: "POST",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: new FormData($('#' + idForm + '')[0]), _method: "PATCH",
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         success: function (data) {
        //             $('#resultUpdate').html(data);
        //         }
        //     });
        // }

        function reloadAlbum() {
            window.location.reload();

        }

    </script>

    <script>
        $(function () {
            $("#sortable").sortable();
            $("#sortable").disableSelection();
        });
        window.getValues = function () {
            $.ajax({
                url: '{{ url('backoffice/sort/album') }}',
                data: $('#frmAlbum').serialize(), _method: "PATCH",
                type: 'POST',
                success: function (data) {
                    $('#image').html(data);
                    // $('#album').load(" #album");

                }
            });
        }
    </script>

@endsection
