@extends("backoffice/layout/components")

@section('top1') Order @endsection

@section('top2') home @endsection

@section('top3') Order detail (ชำระเงินแล้ว) @endsection

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

$active = "select_order";
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
                    แสดงข้อมูล Order
                </h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>รหัส Voucher</th>
                                <th>ชื่อผู้ซื้อ</th>
                                <th>ชื่อ Voucher</th>
                                <th>รหัสส่วนลด</th>
                                <th>สถานะการใช้งาน</th>
                                <th>วันที่ซื้อ</th>
                                <th>วันที่ใช้งาน</th>
                                <th>สถานะการชำระให้โรงแรม</th>
                                <th>ใช้งาน Voucher</th>
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
                {{-- <div class="row">
                    <div class="col-md-10  col-sm-6 col-6 text-right">
                        <h5>ยอดขายรวม</h5>
                    </div>
                    <div class="col-md-2 col-sm-6 col-6 text-right">
                        <h5> {{ number_format($order_detail->total_sales ,2,".",",") }} บาท</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-sm-6 col-6 text-right">
                        <h5>ส่วนลด</h5>
                    </div>
                    <div class="col-md-2 col-sm-6 col-6 text-right">
                        <h5> {{ number_format($sum_discount ,2,".",",") }} บาท</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10  col-sm-6 col-6 text-right">
                        <h5>ยอดขายสุทธิ</h5>
                    </div>
                    <div class="col-md-2 col-sm-6 col-6 text-right">
                        <h5> {{ number_format($order_detail->total_sales - $sum_discount ,2,".",",") }}
                            บาท</h5>
                    </div>
                </div> --}}
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- right col -->
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">รหัสยืนยัน</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <input type="hidden" class="form-control" name="code_confirm" id="code_confirm" value="" />
                <input type="textbox" class="form-control" name="check_code_voucher" id="check_code_voucher" value="" />
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirm_vuocher" onclick="confirm_edit()">ยืนยัน</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<script>
    function editData(id){
        $('#exampleModal').on('show.bs.modal', function (event) {
            $('.modal-body #code_confirm').val(id);
        });
    }
    // $(".show_modal").click(function () {
    //         var code = $(this).attr('atr');
    //         alert(code);
    //         $('#exampleModal').on('show.bs.modal', function (event) {
    //             $('.modal-body #code_confirm').val(code);
    //         });
    //     });
    
    function confirm_edit(){
        var id = $('.modal-body #code_confirm').val();
        var email_send = $("#email_send").val();
        var code = $('.modal-body #check_code_voucher').val();
        var token = $('#token').val();
        $.ajax({
            url: "{{ url('backoffice/order') }}/" + id,
            type: "POST",
            data: {
                _method: 'PUT',
                _token: token,
                id: id,
                code: code,
                email_send: email_send
            },
            success: function (data) {
                console.log(data);
                if (data == 1) {
                    alertify.success('บันทึกสำเร็จ การใช้งาน voucher เรียร้อย   ');
                    setInterval(function () {
                        window.location.reload();
                    }, 3000);

                } else {
                    alertify.error('เงื่อนไขการใช้ voucher ไม่ตรงตามเงื่อนไข');
                }
            }
        });
    }
    // $(".confirm_vuocher").click(function () {

    //     var id = $('.modal-body #code_confirm').val();
    //     var email_send = $("#email_send").val();
    //     var code = $('.modal-body #check_code_voucher').val();
    //     var token = $('#token').val();

    //     console.log(id);
    //     console.log(email_send);
    //     console.log(code);
    //     console.log(token);

    //     $.ajax({
    //         url: "{{ url('backoffice/order') }}/" + id,
    //         type: "POST",
    //         data: {
    //             _method: 'PUT',
    //             _token: token,
    //             id: id,
    //             code: code,
    //             email_send: email_send
    //         },
    //         success: function (data) {
    //             console.log(data);
    //             // if (data == 1) {
    //             //     alertify.success('บันทึกสำเร็จ การใช้งาน voucher เรียร้อย   ');
    //             //     setInterval(function () {
    //             //         window.location.reload();
    //             //     }, 3000);

    //             // } else {
    //             //     alertify.error('เงื่อนไขการใช้ voucher ไม่ตรงตามเงื่อนไข');
    //             // }
    //         }
    //     });
    // });
</script>
@endsection


@section('script')
<input type="hidden" value="{{ csrf_token() }}" id="token">
<div id="resultDelete"></div>
<div id="resultModal"></div>
<input type="hidden" value="0" id="reloadCheck">
<script>


</script>
<script>
    jQuery.noConflict();
    (function ($) {
        $(function () {
            var t = $('#table2').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('backoffice/indexOrder2') }}',
                columns: [{
                        data: 'No',
                        name: 'No'
                    },
                    {
                        data: 'code_voucher',
                        name: 'code_voucher'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'name_voucher',
                        name: 'name_voucher'
                    },
                    {
                        data: 'discount_id',
                        name: 'discount_id'
                    },
                    {
                        data: 'stat_voucher',
                        name: 'stat_voucher'
                    },
                    {
                        data: 'm_create',
                        name: 'm_create'
                    },
                    {
                        data: 'use_date',
                        name: 'use_date'
                    },
                    {
                        data: 'pay_status',
                        name: 'pay_status'
                    },
                    {
                        data: 'edit_status',
                        name: 'edit_status'
                    },
                    {
                        data: 'Manage',
                        name: 'Manage'
                    }
                ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [
                    [1, 'asc']
                ]
            });

            t.on('order.dt search.dt', function () {
                t.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
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
            data: {
                id: id
            },
            type: 'GET',
            success: function (data) {
                console.log(data);
                $('#resultModal').html(data);
                $("#modal-default").modal('show');
                $('#modal-default').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    }


    function valDeleteData(id) {
        var token = $('#token').val();
        $.ajax({
            url: "{{ url('backoffice/order') }}/" + id,
            type: "POST",
            data: {
                _method: 'delete',
                _token: token
            },
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