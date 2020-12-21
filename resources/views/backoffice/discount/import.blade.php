@extends("backoffice/layout/components")

@section('top1') Discount @endsection

@section('top2') home @endsection

@section('top3') Discount detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
    tbody {
        text-align: center;
    }
</style>
<?php
$active = "select_discount";
?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    @if(\Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            <li>{{ \Session::get('success') }}</li>
                        </div><br/>
                    @endif
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <h4><i class="ion ion-clipboard mr-1"></i> แสดงข้อมูล Discount (Multiple Code)</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-dark" href="{{ route('discount.index') }}"> ย้อนกลับ</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{url('backoffice/discount/import')}}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <label><a download href="{{url('storage/ExampleFileDiscount.xlsx')}}">Example Dowload</a></label>
                                <input type="file" name="fileToUpload" required  class="form-control">
                            </div>
                            <div class="col-md-4">
                                <button style="margin-top: 33px" type="submit" class="btn btn-success">Import</button>
                            </div>
                            @csrf
                        </div>
                    </form>
                    <div class="row">
                        {!!   inputSelect2('กรุณาเลือกข้อมูล Discount เพื่อกรองข้อมูล', 'id', 'id', '', 'lg-4', 'required onchange="changeDiscount(this.value)"', $option)!!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table3">
                            <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Date Start</th>
                                <th>Date End</th>
                                <th>Status</th>
                                <th>Minimum Purchase</th>
                                <th>Discount Bath</th>
                                <th>Partner</th>
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
    <input type="hidden" value="{{($id !='' ?$id:0)}}" id="valGet">
    <input type="hidden" value="{{ url("backoffice/data_table/discount") }}" id="urlTable">

    <script>
        function changeDiscount(id) {
            window.location = "import?id=" + id;
        }

        function viewCode(id) {
            $.ajax({
                url: '{{ url("backoffice/discount") }}/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#modal-default").modal('show');
                    $('#modal-default').modal({backdrop: 'static', keyboard: false});
                }
            });
        }

        jQuery.noConflict();
        (function ($) {
            $(function () {
                var idDiscount = $('#valGet').val();
                var urlT = $('#urlTable').val();
                var t = $('#table3').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: urlT + '/' + idDiscount,
                        type: "POST",
                        data: {_token: $('#token').val()},
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'discount_name', name: 'discount_name'},
                        {data: 'Code', name: 'Code'},
                        {data: 'date_start', name: 'date_start'},
                        {data: 'date_end', name: 'date_end'},
                        {data: 'Status', name: 'Status'},
                        {data: 'discount_min', name: 'discount_min'},
                        {data: 'discount_bath', name: 'discount_bath'},
                        {data: 'partner_name', name: 'partner_name'}
                    ],
                    "iDisplayLength": 5,
                    "aLengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    dom: 'lBfrtip',
                    "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[0, 'asc']]
                    // "order": [[1, 'desc']]
                });

                t.on('draw.dt', function () {
                    var PageInfo = $('#table3').DataTable().page.info();
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
                url: '{{ url("backoffice/order") }}/' + id,
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
                url: "{{ url('backoffice/discount') }}/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    console.log(data);
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