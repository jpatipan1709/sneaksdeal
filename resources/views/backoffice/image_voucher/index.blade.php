@extends("backoffice/layout/components")

@section('top1') Voucher Image @endsection

@section('top2') home @endsection

@section('top3') Voucher Image @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "image_voucher";
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
                        แสดงข้อมูลสิ่งอำนวยความสะดวก
                    </h4>

                    <div style="float: right">
                        <a href="image_voucher/create" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i>
                            เพิ่มข้อมูล</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>รูปภาพ</th>
                                        <th>Link</th>
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
                    ajax: '{{ url('backoffice/image_voucher/data_table') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'IMG', name: 'IMG', type: 'html'},
                        {data: 'link', name: 'link'},
                        {data: 'Manage', name: 'Manage'}
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[1, 'asc']]
                });

                t.on('order.dt search.dt', function () {
                    t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
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


        function copyText(id) {
            /* Get the text field */

            var TextSpan = document.getElementById("valueCopy" + id);
            /* Select the text field */
            TextSpan.select();

            /* Copy the text inside the text field */
            // window.clipboardData.setData("Text", input.val());

            document.execCommand("copy");
            /* Alert the copied text */
            alertify.success("Copied: " + TextSpan.value);

        }

        function viewShow(id) {
            $.ajax({
                url: 'image_voucher/' + id,
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
                url: "image_voucher/" + id,
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