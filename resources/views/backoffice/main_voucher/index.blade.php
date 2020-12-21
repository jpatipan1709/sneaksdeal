@extends("backoffice/layout/components")

@section('top1') Main Voucher @endsection

@section('top2') home @endsection

@section('top3') Main Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
    $active = "main_voucher";
    $type = \DB::table('type_vouchers')->get();
@endphp

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
                        แสดงข้อมูล Main Voucher
                    </h4>

                    <div style="float: right">
                        <a href="main_voucher/create" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i>
                            เพิ่มข้อมูล</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ประเภททั้งหมด</label>
                                    <select class="form-control" name="type_vouchers">
                                        <option value="">ทั้งหมด</option>
                                        @foreach($type AS $v)
                                            <option {{($v->code_type == @$_GET['type_vouchers'] ?'selected':'')}} value="{{$v->code_type}}">{{$v->name_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" style="margin-top:28px " class="btn btn-success">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>ชื่อ</th>
                                        <th>Link</th>
                                        <th>แสดงยอดขาย</th>
                                        <th>
                                            <center>จัดการ</center>
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">

            </div>
        </section>

        <!-- /.card -->
        <!-- right col -->
    </div>

@endsection


@section('script')
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <input type="hidden" value="{{ url('backoffice/main_voucher') }}" id="urlDefault">
    <div id="resultDelete"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">
    <input type="hidden" value="{{@$_GET['type_vouchers']}}" id="type_vouchers">

    <script>
        $(function () {
            var type_vouchers = $('#type_vouchers').val();
            var t = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/backoffice/main_voucher/data_table',
                    type: "GET",
                    data: {type: type_vouchers},
                },
                columns: [{
                    data: 'No',
                    name: 'No'
                },
                    {
                        data: 'name_main',
                        name: 'name_main'
                    },
                    {
                        data: 'link',
                        name: 'link'
                    },
                    {
                        data: 'Sale',
                        name: 'Sale'
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
                ],
                stateSave: true
            });

            t.on('draw.dt', function () {
                var PageInfo = $('#table').DataTable().page.info();
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
                url: $('#urlDefault').val() + '/' + id,
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
                url: $('#urlDefault').val() + '/' + id,
                type: "POST",
                data: {
                    _method: 'DELETE',
                    _token: token
                },
                success: function (data) {
                    $('#resultDelete').html(data);
                    window.location = $("#urlDefault").val();

                }

            });
        }

        function changeSale(valId, value) {
            var token = $('#token').val();
            $.ajax({
                url: "backoffice/main/changeSale/" + valId + "",
                type: "GET",
                data: {
                    stat: value
                },
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