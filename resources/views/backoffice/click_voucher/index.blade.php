@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3')  Voucher click detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "total_click_voucher";
?>

@section('contents')
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
                        เแสดงข้อมูลตาราง เก็บข้อมูลการคลิกจองจาก Voucher ประเภทเสนอขายนอกเว็บไซต์
                    </h4>
                    <div style="float: right">
                        <a href="{{url('backoffice/total_click_voucher/export')}}" class="btn btn-outline-danger"><i class="fas fa-file-excel"></i> Export</a>
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
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>โรงแรม (Main)</th>
                                <th>Total Click</th>
                                <th>Voucher</th>

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
    <div id="resultChange"></div>
    <input type="hidden" value="0" id="reloadCheck">
    <input type="hidden" value="{{$date_all}}" id="date_all">

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
                if($(this).is(":checked")){
                    $('#date_start').val("").attr('readonly',true);
                    $('#date_end').val("").attr('readonly',true);
                }else{
                    $('#date_start').val("").removeAttr('readonly');
                    $('#date_end').val("").removeAttr('readonly');
                }
            })
        });
    
            $(function () {
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ url('backoffice/total_click_voucher/data_table') }}',
                        type: "POST",
                        data: {
                            _token: $('#token').val(),
                            date_all:$('#date_all').val(),
                            date_start:$('#date_start').val(),
                            date_end:$('#date_end').val(),
                            main_voucher:$('#main_voucher').val(),
                        },
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name_main', name: 'name_main'},
                        {data: 'Total', name: 'Total'},
                        {data: 'View', name: 'View'},
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    dom: 'lBfrtip',
                    "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],

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



        function viewShow(id) {
            $.ajax({
                url: '{{ url('backoffice/voucher') }}/' + id + '',
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
                url: "voucher/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
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
            $.ajax({
                url: "{{ url('backoffice/voucher__') }}/" + valId + "",
                type: "GET",
                data: {stat: value},
                success: function (data) {
                    $('#resultChange').html(data);
                    $('#reloadCheck').val(10);

                }

            });
        }

        function changeShowFilter(checkbox) {
            if (checkbox.is(':checked')) {
                changeShow(checkbox.val(), 'y')
            } else {
                changeShow(checkbox.val(), 'n')
            }
        }

        function changeSale(valId, value) {
            var token = $('#token').val();
            $.ajax({
                url: "{{ url('backoffice/changeSale') }}/" + valId + "",
                type: "GET",
                data: {stat: value},
                success: function (data) {
                    $('#resultChange').html(data);
                    $('#reloadCheck').val(10);

                }

            });
        }

        function changeShowSale(checkbox) {
            if (checkbox.is(':checked')) {
                changeSale(checkbox.val(), 'y')
            } else {
                changeSale(checkbox.val(), 'n')
            }
        }

    </script>
@endsection