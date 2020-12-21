@extends("backoffice/layout/components")

@section('top1') Report Sales @endsection

@section('top2') Report Sales @endsection

@section('top3') Report detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
tbody{
    text-align: center;
}
</style>
<?php
$active = "report_blog";


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
                    แสดงข้อมูลยอดการขาย
                </h4>
            </div>
            <br/>
            <form action="{{ url('backoffice/ShowPDF/') }}" method="post" >
                @csrf
            <div class="row">
                <div class="col-md-6 offset-md-3 ">
                    {!! inputTimeRank('เลือกวันที่:', 'time_open', 'reservationtime', 'เลือกวันที่ระหว่าง', 'lg-12', 'required','' ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                        <button class="btn btn-success search_date"><i class="fas fa-print"></i> พิมพ์</button>
                </div>     
            </div>
            </form>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                        <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>รหัสออเดอร์</th>
                            <th>ชื่อผู้สั่งซื้อ</th>
                            <th>รหัสส่วนลด</th>
                            <th>สถานะออเดอร์</th>
                            <th>ช่องทางการชำระเงิน</th>
                            <th>
                                <center>จัดการ</center>
                            </th>
                            {{-- <th>พิมพ์</th> --}}
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
        $(function () {
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 30,
                format: 'YYYY/MM/DD HH:mm:ss'

            });
        });
        jQuery.noConflict();
        (function ($) {
            $(function () {
                var t = $('#table2').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/reportBlog') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'id', name: 'o_id'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'discount_id', name: 'discount_id'},
                        {data: 'status_order', name: 'status_order'},
                        {data: 'status_payment', name: 'status_payment'},
                        {data: 'Manage', name: 'Manage'},
                        // {data: 'Print', name: 'Print'}
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

        })(jQuery);


        function viewShow(id) {
            $.ajax({
                url: '{{ url("backoffice/report") }}/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#modal-default").modal('show');
                    $('#modal-default').modal({backdrop: 'static', keyboard: false});
                }
            });
        }


    </script>
@endsection