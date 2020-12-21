@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3') Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
@php $optionBLog = '
<option>--กรุณาเลือกMain Voucher--</option>';
$active = "voucher";
$img = $data->img_show !='' ? url('storage/voucher/'. $data->img_show ) : url('backoffice/dist/img/img-default.jpg');
$imgAlbum = @$file->name !='' ? url('storage/voucher/'.$file->name): url('backoffice/dist/img/img-default.jpg');
foreach ($main as $value){
$optionBLog .=  '
<option value="'.$value->id_main.'" '.($data->relation_mainid == $value->id_main ?'selected':'').'>--'.$value->name_main.'--</option>';
}
$dateO = date_format(date_create($data->date_open ),'m/d/Y H:i' );
$dateC =date_format(date_create($data->date_close ),'m/d/Y H:i' );
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
                    แก้ไขข้อมูล Voucher
                </h4>

                <div class="pull-right" style="float: right">
                    <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                </div>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a   onclick="active_tab('tabFormUpdate')" class="nav-link" href="#tabFormUpdate"
                                            data-toggle="tab" id="tabFormUpdate_li">ฟอร์มแก้ไขข้อมูล</a></li>
                    <li class="nav-item"><a  onclick="active_tab('album')" class="nav-link" href="#album" data-toggle="tab" id="album_li">จัดการอัลบัมรูปภาพ</a>
                    </li>
                </ul>
            </div>


            <!-- /.card-header -->
            <div class="tab-content">
                <div class="tab-pane" id="tabFormUpdate">
                    <form name="frmUpdate" id="frmUpdate" method="post"
                          action="{{ url('backoffice/voucher',$data->voucher_id) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PATCH') }}
                        <div class="card-body">

                            <div class="row">


                                <div class="col-lg-2">
                                    <label>คำนวนยอดขาย Auto</label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="sale_auto" {{($data->stat_sale=='y' ? 'checked':'')}} value="1" id="switch">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-lg-4"><br>
                                    <div id="detailSwitch"
                                         style="display: {{($data->stat_sale == 'y' ? 'none;':'block;')}}"
                                         class="form-group">
                                        <input type="number" name="qty_sale" required placeholder="QTY.." value="{{$data->detail_stat_sale}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                </div>


                                <div class="col-lg-6">

                                    {!! uploadMultipleImage($imgAlbum,'Album Voucher','Size<br>1,620 x 1080 ','lg-12
                                    ','100%','200px') !!}
                                    {!! inputSelect2('กรุณาเลือกข้อมูล Main Voucher', 'main_voucher', 'main_voucher',
                                    '', 'lg-12', 'required',
                                    $optionBLog)!!}
                                    <div class="col-lg-12">
                                        <label>สิ่งอำนวยความสะดวก</label>
                                        @if ($errors->has('facilities'))
                                                <span class="text-danger text-size-error" role="alert">
                                                    {{ $errors->first('facilities') }}
                                                </span>
                                                @endif
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">

                                            @foreach ($facilities as $valueFac)
                                            {!! inputCheckbox( $valueFac->name_facilities, 'facilities[]', '',
                                            (array_search($valueFac->id_facilities,explode(',',$data->relation_facilityid))
                                            > -1 ?'checked':''),
                                            $valueFac->id_facilities) !!}
                                            @endforeach

                                        </div>
                                    </div>
                                    <br>
                                    {!! inputText('ชื่อ Voucher', 'name_voucher', 'id-input', 'Enter ...', 'lg-12',
                                    'required',$data->name_voucher) !!}
                                    {!! inputText('จำนวนผู้เข้าพักสูงสุด (ไม่มีเว้นว่าง)', 'qty_rest', 'id-input', 'Enter ...', 'lg-12',
                                    '',$data->qty_customer) !!}
                                    {!! inputText('จำนวนคืน (ไม่มีเว้นว่าง)', 'qty_night', 'id-input', 'Enter ...', 'lg-12',
                                    '',$data->qty_night) !!}
                                    {!! inputNumber('จำนวน Voucher', 'qty_voucher', 'id-input', 'Enter ...', 'lg-12',
                                    'required',$data->qty_voucher) !!}


                                </div>
                                <div class="col-lg-6">
                                    {!! uploadSingleImage($img,'Image index','Size<br>1,620 x 1080 ','lg-12
                                    ','100%','200px') !!}
                                    {!! inputNumber('ราคาจริง (บาท)', 'price_agent', 'price_agent', 'Enter ...',
                                    'lg-12', 'required',$data->price_agent) !!}
                                    {!! inputNumber('ส่วนลด (บาท)', 'sale', 'sale', 'Enter ...', 'lg-12',
                                    'required',$data->sale) !!}
                                    {!! inputNumber('ราคาขาย (บาท)', 'price', 'price', 'Enter ...', 'lg-12', 'required
                                    readonly',$data->price_sale) !!}
                                    {!! inputTextArea('title', 'title_voucher', 'id-input', '', 'lg-12',
                                    'required',$data->title_voucher) !!}

                                    {!! inputText('ชื่อหัวข้อพิเศษ (ไม่มีเว้นว่าง)', 'name_extra', 'id-input', 'Enter ...', 'lg-12',
                                 '',$data->name_extra) !!}
                                    {!! inputTextArea('รายละเอียดหัวข้อพิเศษ (ไม่มีเว้นว่าง)', 'detail_extra', 'id-input', '', 'lg-12',
                                                                      '',$data->detail_extra) !!}

                                </div>
                                <div class="col-lg-12">
                                    {!! inputTimeRank('เวลาเปิดขาย:', 'time_open', 'reservationtime', 'time rank..',
                                    'lg-12', 'required',$dateO .' - '.$dateC) !!}
                                    {!! inputTextArea('เงื่อนไขการใช้ Voucher', 'term_voucher', 'id-input', 'ckeditor',
                                    'lg-12',
                                    'required',$data->term_voucher) !!}
                                </div>
                            </div>
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-info float-right"><i
                                        class="far fa-edit"></i>
                                Save Edit
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="album">
                    <form name="frmAlbum" id="frmAlbum" method="post"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        {{ method_field('PATCH') }}
                        <div class="card-body">
                            <div class="row" id="sortable">
                                @php $i = 1; @endphp
                                @foreach($album AS $row)
                                <div class="col-lg-3" style="border-color: grey;border-style: solid">
                                    <label>No. {{ $i }}</label><input type="hidden" name="imgSort[]"
                                                                      value="{{ $row->id }}">
                                    <img src="{{ url('storage/voucher/'.$row->name) }}" class="imgAlbum" style="width: 100%;height: 200px;
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

                        </div>
                    </form>

                </div>
            </div>
        </div>


        <!-- /.card -->
    </section>
    <!-- right col -->
</div>
<form id="formDeleteImage" action="{{ url('backoffice/voucher',$data->voucher_id) }}"  name="formDeleteImage" method="post" enctype="multipart/form-data">
    @csrf
    {{ method_field('PATCH') }}
    <input type="hidden" id="deleteImage" name="deleteImage">
</form>
<div id="resultUpdate"></div>
<div id="image"></div>

@endsection

@section('script')

<script>
    $(document).ready(function () {
        $('#sale').blur(function () {
            var price_agent = $('#price_agent').val();
            var sale = $('#sale').val();
            var total = parseFloat(price_agent) - parseFloat(sale);
            $('#price').val(total);
        });


        $('#switch').change(function () {
            if ($('#switch').is(":checked")) {
                $('#detailSwitch').hide();
            } else {
                $('#detailSwitch').show();

            }
        });

        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 30,
            format: 'MM/DD/YYYY HH:mm'

        })


    });

    function reloadAlbum() {
        window.location.reload();

    }

    // function submit(idForm) {
    //     for ( instance in CKEDITOR.instances ) { CKEDITOR.instances[instance].updateElement();}
    //     $.ajax({
    //         url: "{{ url('backoffice/voucher/'.$data->voucher_id.'') }}",
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


    // tab2
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

    $(function () {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
    });
    window.getValues = function () {
        $.ajax({
            url: '{{ url('backoffice/sort/album') }}',
            data:$('#frmAlbum').serialize(), _method:"PATCH",
            type:'POST', success:function (data) {
            $('#image').html(data);
            // $('#album').load(" #album");

        }
    })
        ;
    }

    function active_tab(id) {
        localStorage.setItem("tab_stock", id);
    }

    var tab_id = localStorage.getItem("tab_stock");
        if (tab_id == null) {
            $("#tabFormUpdate_li").addClass('active show');
            $("#tabFormUpdate").addClass('active show');
        } else {
            $("#"+tab_id+"_li").addClass('active show');
            $("#"+tab_id).addClass('active show');
        }
</script>

@endsection
