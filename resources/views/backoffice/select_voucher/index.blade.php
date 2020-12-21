@extends("backoffice/layout/components")

@section('top1') Select Voucher @endsection

@section('top2') home @endsection

@section('top3') Select Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "select_voucher";
?>

@section('contents')
    <style>
         .modal{
            z-index:1050!important;
        }

    </style>
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
                        แสดงข้อมูล Voucher ในแต่ละสถานที่
                    </h4>

                    <div style="float: right">
                        <a href="select_voucher/create" class="btn btn-flat btn-primary"><i class="fas fa-list"></i>
                            จัดเรียง</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>ชื่อ Main Voucher</th>
                            <th>ราคาประมาณ</th>
                            <th>Voucher ที่เลือก</th>
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
    <div id="resultUpdate"></div>
@endsection


@section('script')
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <div id="resultDelete"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">

    <script>
      
            $(function () {
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/select_voucher/data_table') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name_main', name: 'name_main'},
                        {data: 'price_main', name: 'price_main'},
                        {data: 'name_voucher', name: 'name_voucher'},
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
                url: 'select_voucher/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#modal-default").modal('show');
                    $('#modal-default').modal({backdrop: 'static', keyboard: false});
                }
            });
        }


        function modalSelect(id) {
            $.ajax({
                url: "{!! URL("backoffice/select_voucher") !!}/" + id,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: new FormData($('#frmSelectVoucher')[0]), _method: "PATCH",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if(data == 'success'){
                        $('#modal-default').modal("hide");
                        alertify.success('success');
                        $('#reloadCheck').val(10);
                    }else{
                        alertify.error('cancel');

                    }
                    $('#resultUpdate').html(data);
                }
            });
        }


        function valDeleteData(id) {
            var token = $('#token').val();
            $.ajax({
                url: "select_voucher/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    $('#resultDelete').html(data);
                    alertify.success('success');
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