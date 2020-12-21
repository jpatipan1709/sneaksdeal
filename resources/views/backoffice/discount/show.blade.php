<style>
    tr th:only-child {
        width: 100%;
    }

    .table-result {
        max-height: 450px;
    }

    .table-result {
        overflow-y: auto
    }

    .table-result thead th {
        position: sticky;
        background-color: #e9ecef;
        top: -1%;
    }

    td .form-control {
        width: 120px !important;
    }

    .swal2-container {
        z-index: 1110 !important;
    }

</style>
@php
    function statusUsed($stat){
        if($stat == 'yes'){
        $text = '<span class="label label-success">ใช้แล้ว</span>';
        }else{
        $text = '<span class="label label-warning">ยังไม่ใช้</span>';
        }
        return $text;
    }
@endphp
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<div class="modal" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Detail Code</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="table-responsive table-result">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Used</th>
                            <th>Action
                                <button type="button" onclick="AddCode()" id="btn_plush" class="btn btn-warning"><i
                                            class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="top-10  t-body">
                        <tr class="tr-row0" style="display: none">
                            <td colspan="3">
                                <input type="text" placeholder="!Please enter code" class="form-control" id="code"
                                       name="code" style="width: 100%!important;">
                            </td>
                            <td>
                                <button type="button" onclick="checkCode()" title="ตรวจสอบโค๊ด" class="btn btn-primary">
                                    <i
                                            class="fa fa-check"></i>
                                </button>
                                <span id="chk_code" class="danger">รอตรวจสอบ</span>
                            </td>
                            <td>
                                <button type="button" disabled onclick="SubmitCode({{$id}})" id="addCodeBtn"
                                        class="btn btn-success"><i
                                            class="fa fa-save"></i>Add
                                </button>
                            </td>

                        </tr>
                        @foreach($code AS $key => $v)
                            <tr class="tr-row{{($key +1)}}">
                                <td>{{($key +1)}}</td>
                                <td>{{$v->discount_code_multiple}}</td>
                                <td>{!! statusUsed($v->status_used) !!}</td>
                                <td>{{$v->email}}</td>
                                <td>
                                    @if($v->status_used != 'yes')
                                        <button type="button" onclick="delCode('{{($key +1)}}',{{$v->id}})"
                                                class="btn btn-danger"><i
                                                    class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{url('backoffice/discount/check_code')}}" id="urlCheckCode">
<input type="hidden" value="{{url('backoffice/discount/add_code')}}" id="urlAddCode">
<input type="hidden" value="{{url('backoffice/discount/del_sub_code')}}" id="urlDelCode">
<input type="hidden" value="{{csrf_token()}}" id="tokenShow">
<script>
    function delCode(no, id) {
        var urlCode = $('#urlDelCode').val();
        var token = $('#tokenShow').val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
                $.ajax({
                    url: urlCode + '/' + id,
                    type: "POST",
                    data: {_token: token},
                    success: function (res) {
                        $('.tr-row' + no).remove();
                    }
                });

            }
        })
    }

    function checkCode() {
        var code = $('#code').val();
        var token = $('#tokenShow').val();
        var urlCode = $('#urlCheckCode').val();
        $.ajax({
            url: urlCode,
            type: "POST",
            data: {_token: token, code: code},
            success: function (res) {
                if (res == 'true') {
                    Swal.fire({
                        icon: 'success',
                        title: 'This code correct',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#addCodeBtn').attr('disabled', false);
                    $('#chk_code').html("โค๊ดนี้สามารถใช้ได้");
                    $('#chk_code').addClass('success');

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'This code incorrect',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#addCodeBtn').attr('disabled', true);
                    $('#chk_code').addClass('danger');
                    $('#chk_code').html("โค๊ดนี้ไม่สามารถใช้งานได้");

                }
            }
        });
    }

    function CancelAddCode() {
        $('.tr-row0').hide();
        $('#btn_plush').html('<i class="fa fa-plus"></i>');
        $('#btn_plush').attr('onclick', 'AddCode()');
        $('#btn_plush').attr('class', 'btn btn-warning');

    }

    function AddCode() {
        $('.tr-row0').show();
        $('#btn_plush').attr('class', 'btn btn-danger');
        $('#btn_plush').attr('onclick', 'CancelAddCode()');
        $('#btn_plush').html('<i class="fa fa-minus"></i>')
    }

    function SubmitCode(id) {
        var code = $('#code').val();
        var token = $('#tokenShow').val();
        var urlCode = $('#urlAddCode').val();
        $.ajax({
            url: urlCode,
            type: "POST",
            data: {_token: token, code: code,idRef:id},
            success: function (idNew) {
                Swal.fire({
                    icon: 'success',
                    title: 'Add Code success',
                    showConfirmButton: false,
                    timer: 1500
                });
                CancelAddCode();
              var trLength =  $('.t-body tr').length;
                    $('.t-body').append('<tr class="tr-row'+trLength+'">\n' +
                        '                    <td>'+trLength+'</td>\n' +
                        '                    <td>'+code+'</td>\n' +
                        '                    <td><span class="label label-warning">ยังไม่ใช้</span></td>\n' +
                        '                <td></td>\n' +
                        '                <td>\n' +
                        '                <button type="button" onclick="delCode('+trLength+','+idNew+')" class="btn btn-danger"><i class="fa fa-trash"></i>\n' +
                        '                    </button>\n' +
                        '                    </td>\n' +
                        '                    </tr>');
            }
        });

    }
</script>
