@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3')  Voucher click detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "total_click_voucher";
?>

@section('contents')
    <style>
        /*table {*/
        /*    width: 100%;*/
        /*}*/
        /*thead, tbody, tr, td, th { display: block; }*/

        /*tr:after {*/
        /*    content: ' ';*/
        /*    display: block;*/
        /*    visibility: hidden;*/
        /*    clear: both;*/
        /*}*/

        /*thead th {*/
        /*    height: 30px;*/

        /*    !*text-align: left;*!*/
        /*}*/

        /*tbody {*/
        /*    height: 120px;*/
        /*    overflow-y: auto;*/
        /*}*/
        /*thead {*/
        /*    !* fallback *!*/
        /*}*/
        /*tbody td, thead th {*/
        /*    width: 19.2%;*/
        /*    float: left;*/
        /*}*/

    </style>
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            @if(\Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                    <li>{{ \Session::get('success') }}</li>
                </div><br/>
            @endif

            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        Export Total click
                    </h4>
                    <div style="float: right">
                        <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-primary"><i
                                    class="fas fa-arrow-left"></i> ย้อนลับ</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form autocomplete="off" method="get">
                        <input type="hidden" value="{{ csrf_token()}}" name="form_value">
                        <div class="row">
                            <div class="col-lg-9" style="background-color: #e0ecfd8f;border-radius: 15px;padding: 5px">
                                <div class="row">
                                    {!! inputSelect2('Main voucher','main', 'main_voucher', '', 'lg-10', '', $option) !!}
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-12"><h5>กรองข้อมูลวันที่</h5></div>
                                    {!! inputText('วันที่', 'date_start', 'date_start', 'Date ...', 'lg-4', ' autocomplete="off"',$date_start) !!}
                                    {!! inputText('ถึง', 'date_end', 'date_end', 'Date ...', 'lg-4', 'readonly autocomplete="off"',$date_end) !!}
                                    <div class="col-lg-4">
                                        <label>ข้อมูลทั้งหมด</label><br>
                                        <label class="switch">
                                            <input type="checkbox" name="date_all"
                                                   {{$date_all == 'all' ?'checked':''}} value="all">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3" style="  display: flex;
  justify-content: center;
  align-items: center;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                    @if($getForm != '')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" style="max-height: 600px;overflow:auto">
                                    <table class="table table-hover  table-bordered display responsive nowrap"
                                           id="table">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Main Voucher</th>
                                            <th>Voucher</th>
                                            <th>ช่องทางการขาย (Link Website , Tel)</th>
                                            <th>วันที่ซื้อ (Day Time)</th>
                                            <th>Member Click</th>
                                            <th>Guest Click</th>
                                            <th>Total Click</th>
                                            <th>ราคาเต็มก่อนลด</th>
                                            <th>เปอร์เซนต์ที่ลด</th>
                                            <th>ราคาขาย</th>
                                            <th>ยอดขาย (Total Click*ราคาขาย)</th>
                                        </tr>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                        </div>
                @endif
                <!-- /.card-body -->
                    <div class="card-footer clearfix">

                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>
    <input type="hidden" value="{{csrf_token()}}" id="token">
    <input type="hidden" value="{{$date_all}}" id="date_all">
@endsection


@section('script')


    <script>
        $(function () {
            $('#date_start').datepicker({
                format: 'dd-mm-yyyy'
            });
            $('#date_end').datepicker({
                format: 'dd-mm-yyyy',
                // minDate: new Date('25-07-2020'),
            });

            $('#date_start').blur(function () {
                var date_start = $('#date_start').val();
                if (date_start != '') {

                    $('#date_end').removeAttr('readonly');
                } else {
                    $('#date_end').attr('readonly', true);
                }
            });

            $('input[name="date_all"]').click(function () {
                if ($(this).is(":checked")) {
                    $('#date_start').val("").attr('readonly', true);
                    $('#date_end').val("").attr('readonly', true);
                } else {
                    $('#date_start').val("").removeAttr('readonly');
                    $('#date_end').val("").removeAttr('readonly');
                }
            })
        });
        @if($getForm != '')
        jQuery.noConflict();
        (function ($) {
            $(function () {
                var DateStart = $('#date_start').val();
                var DateEnd = $('#date_end').val();
                var DateAll = $('#date_all').val();
                var main_voucher = $('#main_voucher').val();
                if (DateAll != '') {
                    $('title').text("ข้อมูลการคลิกทั้งหมด")
                } else {
                    $('title').text("ข้อมูลการคลิกตั้งแต่วันที่ " + DateStart + " ถึง " + DateEnd + "")
                }
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ url('backoffice/total_click_voucher/data_table/export') }}',
                        type: "POST",
                        data: {
                            _token: $('#token').val(),
                            date_start: DateStart,
                            date_end: DateEnd,
                            date_all: DateAll,
                            main_voucher: main_voucher
                        },
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'Main', name: 'Main'},
                        {data: 'Voucher', name: 'Voucher'},
                        {data: 'DataClick', name: 'DataClick'},
                        {data: 'created', name: 'created'},
                        {data: 'Member', name: 'Member'},
                        {data: 'Guest', name: 'Guest'},
                        {data: 'Total', name: 'Total'},
                        {data: 'PriceAgent', name: 'PriceAgent'},
                        {data: 'Sale', name: 'Sale'},
                        {data: 'PriceSale', name: 'PriceSale'},
                        {data: 'TotalPrice', name: 'TotalPrice'},
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    dom: 'lBfrtip',
                    "buttons": ['excel', 'csv'],
                    "order": [[1, 'asc']],
                    "paging": false,
                    stateSave: true
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
        })(jQuery);
        @endif


    </script>
@endsection