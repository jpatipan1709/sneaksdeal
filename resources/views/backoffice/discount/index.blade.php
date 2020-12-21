@extends("backoffice/layout/components")

@section('top1') Discount @endsection

@section('top2') home @endsection

@section('top3') Discount detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
   #table3 tbody {
        text-align: center;
    }

    @media (min-width: 700px){
    .modal-dialog {
        max-width: 700px!important;
        margin: 1.75rem auto;
    }
    }
</style>
<?php
$active = "select_discount";
?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    @if(\Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            <li>{{ \Session::get('success') }}</li>
                        </div><br/>
                    @endif
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <h4><i class="ion ion-clipboard mr-1"></i> แสดงข้อมูล Discount</h4><br>
                            <a class="btn btn-primary" href="{{ url('backoffice/discount/import') }}"> <i
                                        class="fas fa-plus-square"></i> Export/Import Discounts</a>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-primary" href="{{ route('discount.create') }}"> <i
                                        class="fas fa-plus-square"></i> เพิ่มข้อมูล</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table3">
                            <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Discount Name</th>
                                <th>Discount Code</th>
                                <th>จำนวน</th>
                                <th>โรงแรม</th>
                                <th>วันเริ่มใช้งาน</th>
                                <th>วันหมดอายุ</th>
                                <th>ราคาขั้นต่ำ</th>
                                <th>ราคาลด/บาท</th>
                                <th>สถานะ</th>
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
        function viewCode(id) {
            $.ajax({
                url: '{{ url("backoffice/discount") }}/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#modal-default").modal('show');
                    $('#modal-default').modal({backdrop: 'static', keyboard: false});
                }
            });
        }
        function viewMain(id) {
            $.ajax({
                url: '{{ url("backoffice/discount/viewMain") }}/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#modal-default").modal('show');
                    $('#modal-default').modal({backdrop: 'static', keyboard: false});
                }
            });
        }
       
            $(function () {
                var t = $('#table3').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/indexDiscount') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'discount_name', name: 'discount_name'},
                        {data: 'Code', name: 'Code'},
                        {data: 'discount_qty', name: 'discount_qty'},
                        {data: 'main_hotel', name: 'main_hotel'},
                        {data: 'date_start', name: 'date_start'},
                        {data: 'date_end', name: 'date_end'},
                        {data: 'discount_min', name: 'discount_min'},
                        {data: 'discount_bath', name: 'discount_bath'},
                        {data: 'Status', name: 'Status'},
                        {data: 'Manage', name: 'Manage'}
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    // "order": [[1, 'desc']]
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
                url: "{{ url('backoffice/discount') }}/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    console.log(data);
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