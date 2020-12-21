@extends("backoffice/layout/components")

@section('top1') Voucher @endsection

@section('top2') home @endsection

@section('top3') Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "voucher";
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
        <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">

                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        แสดงข้อมูล Voucher
                    </h4>
                    <form method="get">
                        <div class="row">
                            {!! inputSelect2('เลือก Main voucher', 'main', '', '', 'md-6', '', $option) !!}
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-outline-success" style="margin-top:28px"><i
                                            class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div style="float: right">
                        <a href="{{url('backoffice/voucher/create?main='.$getMain)}}"
                           class="btn btn-flat btn-primary"><i class=" fa fa-plus-square"> </i> เพิ่มข้อมูล</a>
                        <a href="{{ url('/backoffice/VoucherPDF') }}" class="btn btn-flat btn-success"><i
                                    class="fas fa-print"></i> พิมพ์</a>
                        <a href="{{ url('/backoffice/voucher/sort') }}" class="btn btn-flat btn-warning"><i
                                    class="fas fa-list-ul"></i> จัดเรียงดีลที่น่าสนใจ</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>ชื่อ Voucher</th>
                                <th>ชื่อ Main Voucher</th>
                                <th>เวลาเปิดขาย</th>
                                <th>เวลาปิดขาย</th>
                                <th>ประเภท</th>
                                <th>แสดง Voucher</th>
                                <th>แสดงยอดขาย</th>
                                <th>การจัดการหมดเวลา</th>
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
    <div id="resultChange"></div>
    <input type="hidden" value="0" id="reloadCheck">
    <input type="hidden" value="{{$getMain}}" id="main_">

    <script>
     
            $(function () {
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ url("backoffice/voucher/data_table") }}',
                        type: "POST",
                        data: {
                            _token: $('#token').val(),
                            main: $('#main_').val()
                        },
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name_voucher', name: 'name_voucher'},
                        {data: 'name_main', name: 'name_main'},
                        {data: 'date_open', name: 'date_open'},
                        {data: 'date_close', name: 'date_close'},
                        {data: 'Type', name: 'Type'},
                        {data: 'Show', name: 'Show'},
                        {data: 'Sale', name: 'Sale'},
                        {data: 'option', name: 'option'},
                        {data: 'Manage', name: 'Manage'}
                    ],
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

        function changeShowVoucher(checkbox) {
            var token = $('#token').val();
            var valId = checkbox.val();
            if (checkbox.is(':checked')) {
                $.ajax({
                    url: "{{ url('backoffice/change_status_voucher') }}/" + valId + "",
                    type: "GET",
                    data: {stat: 'show'},
                    success: function (data) {
                        $('#resultChange').html(data);
                        $('#reloadCheck').val(10);

                    }

                });
            }
            else {
                $.ajax({
                    url: "{{ url('backoffice/change_status_voucher') }}/" + valId + "",
                    type: "GET",
                    data: {stat: 'hide'},
                    success: function (data) {
                        $('#resultChange').html(data);
                        $('#reloadCheck').val(10);

                    }

                });
            }
        }

    </script>
@endsection