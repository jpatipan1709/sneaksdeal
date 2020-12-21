@extends("backoffice/layout/components")

@section('top1') Member @endsection

@section('top2') home @endsection

@section('top3') Member detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
    tbody {
        text-align: center;
    }
</style>
<?php
$active = "member";


?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
                @if(\Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                        <li>{{ \Session::get('success') }}</li>
                    </div><br />
                @endif
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        แสดงข้อมูล สมาชิก
                    </h4>
                    <div style="float: right">

                        <a href="member/create" class="btn btn-flat btn-primary"><i class=" fa fa-plus-square"> </i> เพิ่มข้อมูล</a>
                        <a href="{{ url('/backoffice/MemberPDF') }}" class="btn btn-flat btn-success"><i class="fas fa-print"></i> พิมพ์</a>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table3">
                            <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>ชื่อ-นามสกุล สมาชิก</th>
                                <th>เบอร์โทร</th>
                                <th>อีเมล</th>
                                <th>ที่อยู่</th>
                                <th>สมาชิกโดย</th>
                                <th>
                                    <center>จัดการ</center>
                                </th>
                            </tr>
                            </thead>
                        </table>
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

@endsection


@section('script')
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <div id="resultDelete"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">

    <script>
        jQuery.noConflict();
        (function ($) {
            $(function () {
                var t = $('#table3').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('/backoffice/member/data_table') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name', name: 'name'},
                        {data: 'tel_member', name: 'tel_member'},
                        {data: 'email', name: 'email'},
                        {data: 'email', name: 'email'},
                        {data: 'stat', name: 'stat'},
                        {data: 'Manage', name: 'Manage'}
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[1, 'asc']]
                });

                t.on('draw.dt', function () {
                    var PageInfo = $('#table3').DataTable().page.info();
                    t.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1 + PageInfo.start;
                    });
                });


                setInterval(function () {
                    var reloadCheck = $('#reloadCheck').val();
                    if (reloadCheck > 0) {
                        t.ajax.reload();
                        $('#reloadCheck').val(0);
                    }
                }, 1300);


            });
        })(jQuery);


        function viewShow(id) {
            $.ajax({
                url: '{{ url("backoffice/order") }}/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#modal-default").modal('show');
                    $('#modal-default').modal({backdrop: 'static', keyboard: false});
                }
            });
        }


        function valDeleteData(id) {
            var token = $('#token').val();
            $.ajax({
                url: "{{ url('backoffice/order') }}/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    $('#resultDelete').html(data);
                    $('#reloadCheck').val(10);

                }

            });
        }


        function deleteData(id) {
            alertify.confirm('!Delete Data', "Do you want delete this data?",
                function () {
                    valDeleteData(id);
                },
                function () {
                    alertify.error('cancel');
                });
        }

    </script>
@endsection