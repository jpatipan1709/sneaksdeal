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
                        List click {{$voucher->name_voucher}}
                    </h4>
                    <div class="pull-right" style="float: right">
                        <a href="#" onclick="go_back()" class="btn btn-flat btn-primary"><i
                                    class="fa fa-arrow-left"> </i> ย้อนกลับ</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Member click: {{number_format($voucherMember)}}</label><br>
                            <label>Guest click: {{number_format($voucherGuest)}}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover  table-bordered display responsive nowrap" id="table">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Type user</th>
                                    <th>Link</th>
                                    <th>Tel</th>
                                    <th>Created at</th>
                                </tr>
                                </thead>

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
    <input value=" List click {{$voucher->name_voucher}}" id="titleChange" type="hidden">
@endsection

@section('script')

    <script>
        jQuery.noConflict();
        (function ($) {
            $(function () {
                var title = $('#titleChange').val();
                $('title').text(title);

                var t = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{{ url('backoffice/total_click_voucher/data_table/list') }}',
                        type: "POST",
                        data: {_token: $('#token').val(), id: '{{$id}}'},
                    },
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'type_user', name: 'type_user'},
                        {data: 'link_click', name: 'link_click'},
                        {data: 'tel_click', name: 'tel_click'},
                        {data: 'created_at', name: 'created_at'},
                    ],
                    dom: 'lBfrtip',
                    "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],

                    "order": [[4, 'DESC']],
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


    </script>

@endsection
