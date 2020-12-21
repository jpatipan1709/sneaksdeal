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
$active = "report_product";


?>
@section('contents')
<div class="row">
    <!-- Left col -->
    <section class="col-lg-12 ">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <div class="row">
                    <div class="col-md-6 text-left">
                       <h4> <i class="ion ion-clipboard mr-1"></i> แสดงข้อมูลสินค้า</h4>
                    </div>     
              
                    <div class="col-md-6 text-right">
                        <a href="{{ url('/backoffice/ShowPDF2') }}" class="btn btn-success search_date"><i class="fas fa-print"></i> พิมพ์</a>
                    </div>     
                </div>
            </div>
            <br/>
          
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                        <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>ชื่อ Voucher</th>
                            <th>วันที่เริ่ม</th>
                            <th>วันที่สิ้นสุด</th>
                            <th>ราคาเต็ม</th>
                            <th>ราคาขาย</th>
                            <th>ชื่อ บล็อก</th>
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
                    ajax: '{{ url('backoffice/reportProductTB') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name_voucher', name: 'name_voucher'},
                        {data: 'date_open', name: 'date_open'},
                        {data: 'date_close', name: 'date_close'},
                        {data: 'price_agent', name: 'price_agent'},
                        {data: 'price_sale', name: 'price_sale'},
                        {data: 'name_blog', name: 'name_blog'},
                        {data: 'Manage', name: 'Manage'},
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[1, 'asc']]
                });

                t.on('order.dt search.dt', function () {
                    t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

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
                url: '{{ url("backoffice/reportshow") }}/' + id,
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