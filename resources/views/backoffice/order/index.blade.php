@extends("backoffice/layout/components")

@section('top1') Order @endsection

@section('top2') home @endsection

@section('top3') Order detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
    tbody {
        text-align: center;
    }

    .modal {
        z-index: 1101 !important;
    }
</style>

<?php

$active = "select_order";

$statusOption = '<option value="all" '.(@$stat == 'all'?'selected':'').'>ทั้งหมด</option>';
$statusOption .= '<option value="000" '.(@$stat == '000'?'selected':'').'>ชำระเงินแล้ว</option>';
$statusOption .= '<option value="001" '.(@$stat == '001'?'selected':'').'>กำลังดำเนินการ</option>';
$statusOption .= '<option value="002" '.(@$stat == '002'?'selected':'').'>ยกเลิกการชำระ</option>';
$statusOption .= '<option value="no"  '.(@$stat == 'no'?'selected':'').'>ยกเลิก</option>';
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
                        <i class="ion ion-clipboard mr-1"></i>
                        แสดงข้อมูล Order
                    </div>
                    <div class="col-md-6 text-right">
                       <a  href="#" id="print_report" class="btn btn-flat btn-success"><i class="fas fa-print"></i> พิมพ์</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form action="{{ url('backoffice/indexOrder') }}" method="post">
                        <div class="row">
                            {!! inputSelect('สถานะ', 'status', 'status', '', 'md-4', 'required',$statusOption) !!}
                            <div class="col-md-4">
                                <div class="col-xs-12 col-lg-12">
                                    <div class="form-group">
                                        <label>โรงแรม:</label>
                                        <div class="input-group">
                                            <select class="form-control" name="main_hotel" id="main_hotel">
                                                @php
                                                $main_vouchers = DB::table('main_voucher')->whereNull('deleted_at')->get();
                                                @endphp
                                                <option value="0">ทั้งหมด</option>
                                                @foreach ($main_vouchers as $main_voucher)
                                                <option {!! @$m== $main_voucher->id_main ? 'selected' : '' !!} value="{{
                                                    $main_voucher->id_main }}">{{ $main_voucher->name_main }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                {!! inputTimeRank('เวลาเปิดขาย:', 'time_open', 'reservationtime', 'time rank..',
                                'lg-12', 'required',(@$d !='all'?@str_replace(' / ',' - ',str_replace('-','/',$d)):'' )
                                ) !!}
                            </div>

                            <div class="col-md-12">
                                <center>
                                <label style="color:white">.</label>
                                <a href="javascript:" onclick="filter_order()" class="btn btn-primary form-control">ค้นหา</a>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                        <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>รหัส Order</th>
                            <th>รหัส Voucher</th>
                            <th>ชื่อผู้ซื้อ</th>
                            <th>ชื่อโรงแรม</th>
                            <th>ราคา</th>
                            <th>สถานะ Order (ชำระเงิน)</th>
                            <th>วันที่ซื้อ</th>
                            <th>วันที่ใช้งาน</th>
                            <th>สถานะการใช้งาน</th>
                            <th> <center>จัดการ</center></th>
                            <th>ส่ง Voucher ตัว Scan</th>
                            <th>ส่ง Voucher ตัวจริง </th>
                            <th>สถานะการชำระให้โรงแรม</th>
                        </tr>
                        </thead>

                    </table>
                </div>
              
            </div>
            <!-- /.card-body -->
            {{-- <div class="card-footer clearfix">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <h4>ยอดขายรวม {{ number_format($sum_order->total_sales ,2, '.', ',') }} บาท</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <h4>ส่วนลด {{ number_format($sum_discount ,2, '.', ',') }} บาท</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <h4>ยอดขายสุทธิ {{ number_format($sum_order->total_sales - $sum_discount ,2, '.', ',') }} บาท</h4>
                    </div>
                </div>

            </div> --}}
        </div>
        <!-- /.card -->
    </section>
    <!-- right col -->
</div>

@endsection

@section('script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<input type="hidden" value="{{ csrf_token() }}" id="token">
<div id="resultDelete"></div>
<div id="resultModal"></div>
<input type="hidden" value="0" id="reloadCheck">
<input type="hidden" id="url" value="{{ url('backoffice/indexOrder') }}">
<input type="hidden" id="m" value="{{ @$m }}">
<input type="hidden" id="d" value="{{ @$d }}">
<script>

    $(function () {
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 30,
            format: 'YYYY/MM/DD HH:mm:ss'

        });
    });
    $(function () {
        $("#print_report").click(function(){
            
       var date = $("#reservationtime").val();
       var hotel = $("#main_hotel").val();
        var query = {
            date: $('#reservationtime').val(),
            hotel: $('#main_hotel').val(),
            status: $('#status').val()
        }
                var url = "{{ url('backoffice/OrderPDF') }}?" + $.param(query)
                window.location = url;
        });
    });
  

        $(function () {
            var m = $("#m").val();
            var d = $("#d").val();
            var status = $("#status").val();
            var url = $("#url").val();
            if (m != "") {
                var url_new = url + '/' + m + '/' + d;
            } else {
                var url_new = url;

            }
            var t = $('#table2').DataTable({
                // "lengthMenu": [20, 40, 60, 80, 100],
                // "pageLength": 20,
                "iDisplayLength": 100,
                "aLengthMenu": [
                    [20, 40, 60, 80, 100, -1],
                    [20, 40, 60, 80, 100, "All"]
                ],
                dom: 'lBfrtip',
                "buttons": [ 'excel', 'csv'],
                processing: true,
                serverSide: true,
                ajax: {
                    url: url_new,
                    type: "POST",
                    data: {status: status, _token: $('#token').val()}
                },
                columns: [
                    {data: 'No', name: 'No'},
                    {data: 'id', name: 'id'},
                    {data: 'code_voucher', name: 'code_voucher'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'name_main', name: 'name_main'},
                    {data: 'priceper', name: 'priceper'},
                    {data: 'status_order3', name: 'status_order3'},
                    {data: 'm_create', name: 'm_create'},
                    {data: 'use_date', name: 'use_date'},
                    {data: 'stat_voucher', name: 'stat_voucher'},
                    {data: 'Manage', name: 'Manage'},
                    {data: 'SendVoucherScan', name: 'SendVoucherScan'},
                    {data: 'SendVoucherTrue', name: 'SendVoucherTrue'},
                    {data: 'pay_status', name: 'pay_status'},

                ],
                columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                order: [
                    [1, 'desc']
                ]
            });

            t.on('draw.dt', function () {
                var PageInfo = $('#table2').DataTable().page.info();
                t.column(0, {
                    page: 'current'
                }).nodes().each(function (cell, i) {
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
            data: {
                id: id
            },
            type: 'GET',
            success: function (data) {
                $('#resultModal').html(data);
                $("#modal-default").modal('show');
                $('#modal-default').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    } 
    
    function viewSendVoucher2(id) {
        $.ajax({
            url: '{{ url("backoffice/sendVoucherTrue") }}/' + id,
            data: {
                id: id
            },
            type: 'GET',
            success: function (data) {
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

    function changeShow(valId, value) {

        var token = $('#token').val();
        swal({
            title: "คำเตือน?",
            text: "คุณต้องการ เปลี่ยนแปลง สถานะการจ่ายเงิน ใช่หรือไม่",
            icon: "warning",
            buttons: [
                'ไม่, ยกเลิก!',
                'ใช่, ฉันแน่ใจ!'
            ],
            dangerMode: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ url('backoffice/changeorder') }}/" + valId + "",
                    type: "GET",
                    data: {stat: value},
                    success: function (data) {
                        swal({
                            title: 'เรียบร้อย!',
                            text: 'คุณทำการเปลี่ยนแปลงสถานะการจ่ายเงิน!',
                            icon: 'success'
                        }).then(function () {
                            // console.log(data);
                            $('#resultChange').html(data);
                            $('#reloadCheck').val(10);
                        });
                    }
                });
            } else {
                swal("ยกเลิก", "คุณยกเลิกการเปลี่ยนแปลงสถานะการจ่ายเงิน", "error");

                $('#reloadCheck').val(10);
            }
        })

    }

    function changeShowFilter(checkbox) {

        if (checkbox.is(':checked')) {
            changeShow(checkbox.val(), '1')
        } else {
            changeShow(checkbox.val(), '0')
        }
    } 
    
     function changeVoucher(valId, value) {

        var token = $('#token').val();
        swal({
            title: "คำเตือน?",
            text: "คุณต้องการ ยืนยันการส่งคูปอง",
            icon: "warning",
            buttons: [
                'ไม่, ยกเลิก!',
                'ใช่, ฉันแน่ใจ!'
            ],
            dangerMode: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ url('backoffice/changeSendVoucher') }}/" + valId + "",
                    type: "GET",
                    data: {stat: value},
                    success: function (data) {
                        swal({
                            title: 'เรียบร้อย!',
                            text: 'คุณทำการเปลี่ยนแปลงสถานะการจ่ายเงิน!',
                            icon: 'success'
                        }).then(function () {
                            // console.log(data);
                            $('#resultChange').html(data);
                            $('#reloadCheck').val(10);
                        });
                    }
                });
            } else {
                swal("ยกเลิก", "คุณยกเลิกการเปลี่ยนแปลงสถานะการจ่ายเงิน", "error");

                $('#reloadCheck').val(10);
            }
        })

    }
    function changeSendVoucher(checkbox) {
        if (checkbox.is(':checked')) {
            changeVoucher(checkbox.val(), '1')
        } else {
            changeVoucher(checkbox.val(), '0')
        }
    }

    function filter_order() {
        var main_hotel = $("#main_hotel").val();
        var status = $("#status").val();
        var time_open = $("#reservationtime").val();
        for (i = 1; i < 5; i++) {
            time_open = time_open.replace("/", "-");
        }
        // time_open = time_open.replace("/", "-");
        if (time_open == "") {
            time_open = "all";
        }

        // alert(time_open);

        if (main_hotel != "" || time_open != "") {
            window.location = "{{ url('backoffice/order') }}/" + main_hotel + "/" + time_open+"/"+status;

        }
    }

 
</script>
@endsection