@extends("backoffice/layout/components")

@section('top1') Bulk Upload @endsection

@section('top2') home @endsection

@section('top3') Bulk Upload detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "bulk_upload";
?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            @if(\Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                    <li>{{ \Session::get('success') }}</li>
                </div><br/>
        @endif
        <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;padding: 15px">
                <form method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Upload file (Excel/.xlsx)  <a href="https://docs.google.com/spreadsheets/d/1EaMgMyYtMBXeQ8wbuR2ViuF5hdFzrniYwPUsNmmPxb0/edit#gid=0" target="_blank" style="color: red">>>Example Form Excel</a></h5>
                        </div>
                        <div class="col-md-4">
                            <input type="file" required accept=".xlsx,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file" id="file" class="inputfile" />
                             <button style="float: right" type="submit" class="btn btn-outline-success" ><i
                                        class="fa fa-save"></i> save</button>
                        </div>

                    </div>
                </form>

            </div>
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        เแสดงข้อมูลตาราง ประวัติ Bulk upload
                    </h4>


                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                            <thead>
                            <tr>
                                <th>ลำดับ.</th>
                                <th>จำนวน โรงแรม</th>
                                <th>จำนวน Voucher</th>
                                <th>เวลานำเข้าข้อมูล</th>
                                <th>จัดการ</th>

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
    <div id="resultChange"></div>
    <input type="hidden" value="0" id="reloadCheck">

    <script>
      
            $(function () {
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ url('backoffice/bulk_upload/data_table') }}',
                        type: "POST",
                        data: {_token: $('#token').val()},
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'main_total', name: 'main_total'},
                        {data: 'voucher_total', name: 'voucher_total'},
                        {data: 'created_at', name: 'created_at'},
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
                    var PageInfo = $('#table').DataTable().page.info();
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
   


        function viewShow(id) {
            $.ajax({
                url: '{{ url('backoffice/bulk_upload') }}/' + id + '',
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
                url: "/backoffice/bulk_upload/" + id,
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