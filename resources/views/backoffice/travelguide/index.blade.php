@extends("backoffice/layout/components")

@section('top1') Travel Guide @endsection

@section('top2')  Travel Guide @endsection

@section('top3')  Travel Guide detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
tbody{
    text-align: center;
}
</style>
<?php

$active = "travel_guide";
?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
                @if(\Session::has('success'))
                <div class="alert alert-success">
                    <li>{{ \Session::get('success') }}</li>
                </div><br />
                @endif
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        แสดงข้อมูล Travel Guide
                    </h4>

                    <div style="float: right">
                        <a href="/backoffice/travelguidemanage/create" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i>
                            เพิ่มข้อมูล</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                       
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                        <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>ชื่อ Category</th>
                            <th>วันที่อัพเดท</th>
                            <th>สถานะ</th>
                            <th>
                                <center>จัดการ</center>
                            </th>
                        </tr>
                        </thead>
                    </table>
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
         $('div.alert').delay(5000).slideUp(500);


            $(function () {
                var t = $('#table2').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/indexTralvel') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'tg_name', name: 'tg_name'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'tg_status', name: 'tg_status'},
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
                    var PageInfo = $('#table2').DataTable().page.info();
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


        function valDeleteData(id) {
            var token = $('#token').val();
            $.ajax({
                url: "{{ url('backoffice/travelguidemanage') }}/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    $('#resultDelete').html(data);
                    $('#reloadCheck').val(10);
                }

            });
        }


        function deleteData(id) {
            alertify.confirm('!Delete Data',"Do you want delete this data?",
                function(){
                    valDeleteData(id);
                },
                function(){
                    alertify.error('cancel');
                });
        }

    </script>
@endsection