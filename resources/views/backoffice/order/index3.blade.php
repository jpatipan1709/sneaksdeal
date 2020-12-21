@extends("backoffice/layout/components")

@section('top1') Order @endsection

@section('top2') home @endsection

@section('top3') Order detail(ยังไม่ได้ชำระเงิน) @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
tbody{
    text-align: center;
}
.modal{
    z-index: 1101!important;
}
</style>
<?php

$active = "select_order2";
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
                        แสดงข้อมูล Order  (ยังไม่ได้ชำระเงิน)
                    </h4>
                </div>
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
                                <th>ยอดขาย</th>
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
                        <div class="row">
                                <div class="col-md-6 text-left">
                                    <h4>ยอดขายทั้งหมด</h4>
                                </div>
                                <div class="col-md-6 text-right">
                                   <h4> ฿ {{ number_format($order_detail->total_sales,2,".",",") }} บาท</h4> 
                                </div>
                            </div>
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
                var t = $('#table2').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/indexOrder3') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'id', name: 'o_id'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'discount_id', name: 'discount_id'},
                        {data: 'status_order', name: 'status_order'},
                        {data: 'status_payment', name: 'status_payment'},
                        {data: 'order_total', name: 'order_total'},
                        {data: 'Manage', name: 'Manage'}
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
                url: '{{ url("backoffice/ordershow") }}/' + id,
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