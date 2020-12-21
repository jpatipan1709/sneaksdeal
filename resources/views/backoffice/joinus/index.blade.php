@extends("backoffice/layout/components")

@section('top1') Joinus @endsection

@section('top2') home @endsection

@section('top3') Joinus detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "joinus";
?>

@section('contents')
<style>
    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 0px solid #dee2e6;
    }
    </style>
<div class="row">
    <!-- Left col -->
    <section class="col-lg-12 ">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        Joinus
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ url('/backoffice/showjoinindex') }}" class="btn btn-primary">ฟอร์ม Join us</a>
                    </div>
                </div>
                <h4 class="pull-left">
                  
                   
                </h4>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover  table-bordered table-striped display responsive nowrap" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Name</th>
                                <th>Hotel</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Comment</th>
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
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <div id="resultDelete"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">
<script>

        $(function () {
            var t = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('/backoffice/joinus/data_table') }}',
                columns: [
                    {data: 'No',name: 'No'},
                    {data: 'ju_name',name: 'ju_name'},
                    {data: 'ju_hotel',name: 'ju_hotel'},
                    {data: 'ju_tel',name: 'ju_tel'},
                    {data: 'ju_email',name: 'ju_email'},
                    {data: 'ju_content',name: 'ju_content'},
                    {data: 'Manage',name: 'Manage'}
                ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [
                    [0, 'asc']
                ]
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
                url: '{{ url("backoffice/joinus/showview") }}/' + id,
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
                url: "{{ url('backoffice/joinus/joinusdel') }}/" + id,
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
</script>
@endsection