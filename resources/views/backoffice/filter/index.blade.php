@extends("backoffice/layout/components")

@section('top1') Filter @endsection

@section('top2') home @endsection

@section('top3') Filter detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "filter";
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
                        Filter By
                    </h4>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>ชื่อ Filter</th>
                                <th>
                                    <center>จัดการการแสดง</center>
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
    <div id="resultChange"></div>
    <input type="hidden" value="0" id="reloadCheck">

    <script>
     
            $(function () {
                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/filter/data_table') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'name_filter', name: 'name_filter'},
                        {data: 'Manage', name: 'Manage'}
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[0, 'asc']]
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

        function changeShow(valId,value) {
            var token = $('#token').val();
            $.ajax({
                url: "{{ url('backoffice/filter') }}/"+ valId +"" ,
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
                changeShow(checkbox.val(),'y')
            } else {
                changeShow(checkbox.val(),'n')
            }
        }




    </script>
@endsection