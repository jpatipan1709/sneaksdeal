@extends("backoffice/layout/components")

@section('top1') Travel Guide @endsection

@section('top2')  Travel Guide @endsection

@section('top3')  Travel Guide detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<style>
tbody{
    text-align: center;
}
</style>
<?php

$active = "travel_guide";
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
                        เพิ่มข้อมูล Travel Guide
                    </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                        @if(\Session::has('success'))
                        <div class="alert alert-success">
                            <li>{{ \Session::get('success') }}</li>
                        </div><br />
                        @endif
                        @if(\Session::has('warning'))
                        <div class="alert alert-warning alert-dismissible" >
                            <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                            <li>{{ \Session::get('warning') }}</li>
                        </div><br />
                        @endif
                    <form action="{{ route('travelguidemanage.store') }}" method="post" id="frmUpdate">
                        @csrf                                        
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="travel_name">Travel Guide Tag</label>
                                <input type="textbox" class="form-control" id="travel_name" name="travel_name" placeholder="ชื่อ Travel Guide Tag" value="">
                                @if ($errors->has('travel_name'))
                                    <span class="text-danger text-size-error" role="alert">
                                        {{ $errors->first('travel_name') }}
                                    </span>
                                @endif
                              </div>
                            </div>
                           
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                                <a href="{{ url('backoffice/travelguidemanage') }}"  class="btn btn-dark float-left">ย้อนกลับ</a>
                        </div>
                        <div class="form-group col-md-6">
                                <button href="#" class="btn btn-info float-right" type="submit"><i class="fas fa-plus"></i>  บันทึก</button>
                        </div>
                    </div>
                
              </form>
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
        jQuery.noConflict();
        (function ($) {
            $(function () {
                var t = $('#table2').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('backoffice/indexTralvel') }}',
                    columns: [
                        {data: 'No', name: 'No'},
                        {data: 'id', name: 'o_id'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'discount_id', name: 'discount_id'},
                        {data: 'status_order', name: 'status_order'},
                        {data: 'status_payment', name: 'status_payment'},
                        {data: 'Manage', name: 'Manage'}
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    "order": [[1, 'asc']]
                });
                
                t.on( 'order.dt search.dt', function () {
                    var PageInfo = $('#table2').DataTable().page.info();
                    t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                        cell.innerHTML = i + 1 + PageInfo.start;
                    } );
                } );

               




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
                url: "{{ url('backoffice/order') }}/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    $('#resultDelete').html(data);
                    $('#reloadCheck').val(10);

                }

            });
        }


        function deleteData(id) {
            alertify.confirm('!Delete Data',"Do you want delete this data?",
                function(){
                    valDeleteData(id);
                },
                function(){
                    alertify.error('cancel');
                });
        }

    </script>
@endsection