@extends("backoffice/layout/components")

@section('top1') Sorting Show Voucher @endsection

@section('top2')  home @endsection

@section('top3') Sorting Show Voucher @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
    $active = "voucher";
    $option = '<option value="">กรุณาเลือก Voucher</option>';
foreach ($voucher AS $value){
$option .= '<option value="'.$value->voucher_id.'">'.$value->name_voucher.'</option>' ;
}
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
                        จัดเรียง Voucher ที่น่าสนใจ
                    </h4>


                </div>
                <div class="card-body">
                    <form name="frmAdd" id="frmAdd">

                        <div class="row">
                            {!! inputSelect2('เลือก Voucher','voucher','voucher','','lg-6','required',$option) !!}
                            <div class="col-lg-6">
                                <br>

                                    <button type="button" id="subAdd"   onclick="add()" style="margin-top: 5px;display:{!! count($sort) < 4?'block':'none' !!}" class="btn btn-info">
                                        <i
                                                class="fas fa-plus-circle"></i>
                                    </button>

                            </div>
                        </div>
                    </form>

                    <hr>
                    <div class="row">
                        <div class="col-lg-12"><label>เลือกได้ทั้งหมด 4 รายการ</label></div>
                        <div class="col-lg-6">
                            <form name="form1" method="post" id="sortVoucher">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="table-responsive-lg ">
                                    <table class="table " id="table_sort">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Sort</th>
                                            <th>Manage</th>
                                        </tr>
                                        </thead>
                                        <tbody id="sortable">
                                        @foreach($sort AS $key => $value)
                                            <tr>
                                                <td>
                                                    No.{!! $key+1 !!}
                                                </td>
                                                <td>
                                                    {{ $value->name_voucher }}
                                                </td>
                                                <td>{{ $value->sort_view }}
                                                    <input type="hidden" name="sort[]">
                                                    <input type="hidden" name="voucher[]"
                                                           value="{{ $value->voucher_id }}">
                                                </td>
                                                <td><a href="javascript:" title="คลิกลาก" class="btn btn-warning"><i
                                                                class="fab fa-dropbox"></i><i
                                                                class="fas fa-eye-dropper"></i></a>

                                                    <a href="javascript:" onclick="del({{ $value->id_sorting }})"
                                                       title="ลบ"
                                                       class="btn btn-danger"><i
                                                                class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-lg-12">
                                    <center>
                                        <button type="submit" class="btn btn-primary"><i
                                                    class="fas fa-pencil-alt"></i>Save Sort
                                        </button>
                                    </center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- /.card-body -->
                <div class="card-footer clearfix">

                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>
<div id="resultSort"></div>
@endsection


@section('script')
    <script>
        $(function () {
            $("#sortable").sortable();
            $("#sortable").disableSelection();
        });
        $("#sortVoucher").on("submit", function () {
            $.ajax({
                url: "{{url('backoffice/voucher/sort')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#sortVoucher').serialize(),
                success: function (data) {
                    $('#resultSort').html(data);

                }
            });
            return false;

        });
        function add() {
            var voucher = $('#voucher').val();
            if (voucher != '') {
                $.ajax({
                    url: '{{ url('backoffice/voucher/sort/create') }}',
                    data: $('#frmAdd').serialize(),
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        var tr = null;
                        for (i = 0; i < data.length; i++) {
                            tr += '<tr>\n' +
                                '                                                <td>\n' +
                                '                                                    No. ' + (i + 1) + '\n' +
                                '                                                <td>\n' +
                                '                                                <td>' + data[i].name_voucher + '\n' +
                                '                                                    <input type="hidden" name="sort[]">\n' +
                                '                                                    <input type="hidden" name="voucher[]"\n' +
                                '                                                           value="' + data[i].voucher_id + '">\n' +
                                '                                                </td>\n' +
                                '                                                <td><a href="javascript:" title="คลิกลาก" class="btn btn-warning"><i\n' +
                                '                                                                class="fab fa-dropbox"></i><i\n' +
                                '                                                                class="fas fa-eye-dropper"></i></a>\n' +
                                '\n' +
                                '                                                    <a href="javascript:" onclick="del(' + data[i].id_sorting + ')"\n' +
                                '                                                       title="ลบ"\n' +
                                '                                                       class="btn btn-danger"><i\n' +
                                '                                                                class="fas fa-trash-alt"></i></a>\n' +
                                '                                                </td>\n' +
                                '                                            </tr>'
                        }
                        alertify.success('Add success');
                        if(data.length >= 4 ){
                            $('#subAdd').hide();
                        }else{
                            $('#subAdd').show();

                        }
                        $('#table_sort tbody').html(tr);
                    }
                });
            } else {
                alertify.error('กรุณาเลือก Voucher');

            }
        }

        function del(id) {
            $.ajax({
                url: '{{ url('backoffice/voucher/sort') }}/' + id,
                data: {id_sort: id},
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    var tr = null;
                    for (i = 0; i < data.length; i++) {
                        tr += '<tr>\n' +
                            '                                                <td>\n' +
                            '                                                    No. ' + (i + 1) + '\n' +
                            '                                                <td>\n' +
                            '                                                <td>' + data[i].name_voucher + '\n' +
                            '                                                    <input type="hidden" name="sort[]">\n' +
                            '                                                    <input type="hidden" name="voucher[]"\n' +
                            '                                                           value="' + data[i].voucher_id + '">\n' +
                            '                                                </td>\n' +
                            '                                                <td><a href="javascript:" title="คลิกลาก" class="btn btn-warning"><i\n' +
                            '                                                                class="fab fa-dropbox"></i><i\n' +
                            '                                                                class="fas fa-eye-dropper"></i></a>\n' +
                            '\n' +
                            '                                                    <a href="javascript:" onclick="del(' + data[i].id_sorting + ')"\n' +
                            '                                                       title="ลบ"\n' +
                            '                                                       class="btn btn-danger"><i\n' +
                            '                                                                class="fas fa-trash-alt"></i></a>\n' +
                            '                                                </td>\n' +
                            '                                            </tr>'
                    }
                    alertify.success('Delete success');
                    if(data.length >= 4 ){
                        $('#subAdd').hide();
                    }else{
                        $('#subAdd').show();

                    }
                    $('#table_sort tbody').html(tr);

                    // $('#table_sort').ajax.reload();
                }
            });
        }
    </script>
@endsection