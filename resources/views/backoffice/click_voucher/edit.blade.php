@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3') Voucher click detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "total_click_voucher";


?>

@section('contents')

    <div class="row">
        <section class="col-lg-12 ">
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        ผลรวมการคลิกจองแต่ละ Voucher
                    </h4>
                    <div class="pull-right" style="float: right">
                        <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                    class="fa fa-arrow-left"> </i> ย้อนกลับ</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {!! inputSelect2('Main Voucher', 'main', 'main', '', 'md-6', 'required', $option) !!}
                        <div class="col-md-6">
                            <button type="button" onclick="goMain($('#main').val())" class="btn btn-outline-success"
                                    style="margin-top:28px"><i
                                        class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <form autocomplete="off" method="get">
                        <input type="hidden" value="{{ csrf_token()}}" name="form_value">
                        <div class="row">
                            <div class="col-lg-9" style="background-color: #e0ecfd8f;border-radius: 15px;padding: 5px">
                                <div class="row">
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
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Voucher</th>
                                    <th>Member click</th>
                                    <th>Guest click</th>
                                    <th>list click</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th colspan="2">
                                        <span style="float: right"> รวม </span>
                                    </th>
                                    <th id="sumMember"></th>
                                    <th id="sumGuest"></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </div>
    {{--<script>--}}
    {{--alertify.error('sss');--}}
    {{--</script>--}}
    <div id="resultCreate"></div>
    <input value="{{csrf_token()}}" id="token" type="hidden">
    <input value="{{$date_all}}" id="date_all" type="hidden">
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
        })
        jQuery.noConflict();
        (function ($) {
            $(function () {
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ url('backoffice/total_click_voucher/data_table/main') }}',
                        type: "POST",
                        data: {
                            _token: $('#token').val(),
                            id: '{{$id}}',
                            date_all: $('#date_all').val(),
                            date_start: $('#date_start').val(),
                            date_end: $('#date_end').val(),
                        },
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name_voucher', name: 'name_voucher'},
                        {data: 'Total_Guest', name: 'Total_Guest'},
                        {data: 'Total_Member', name: 'Total_Member'},
                        {data: 'View', name: 'View'},
                    ],
                    drawCallback: function () {
                        var api = this.api().column( 3, {page:'current'} ).data();
                        var sum = 0;
                        for (i = 0 ; i < api.length ; i++){
                            sum +=  parseFloat(api[i])
                        }
                        $('#sumGuest').html(sum)
                        var api = this.api().column( 2, {page:'current'} ).data();
                        var sum = 0;
                        for (i = 0 ; i < api.length ; i++){
                            sum +=  parseFloat(api[i])
                        }
                        $('#sumMember').html(sum)
                        // $( api.table().footer() ).html(
                        //     api.column( 3, {page:'current'} ).data().sum()
                        // );
                    },
                    dom: 'lBfrtip',
                    "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[1, 'asc']],
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

        function goMain(id) {
            window.location = "/backoffice/total_click_voucher/" + id + "/edit";
        }
    </script>

@endsection
