@extends("backoffice/layout/components")

@section('top1') Blog Image @endsection

@section('top2') home @endsection

@section('top3') Blog Image @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "image_blog";
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
                    แสดงข้อมูล Image Blog
                </h4>
                <div class="row">
                    <div class="col-12">
                        <a href="javascript:void(0)" onclick="manageAlbum()" class="btn btn-flat btn-success"><i class="fa fa-images"></i>
                            จัดการอัลบั้ม</a>


                        <a href="image_blog/create" class="btn btn-flat btn-primary"><i class=" fa fa-plus-square"> </i>
                            เพิ่มข้อมูล</a>

                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="get">
                    <div class="row">
                        {!! inputSelect2('เลือกอัลบั้ม', 'album', '', '', 'md-3', '', $option) !!}
                        <div class="col-xs-12 col-md-3">
                        <div class="form-group">
                            <label>วันที่สร้าง</label>
                        <input type="text" readonly value="{{$date}}" name="date" id="datepicker_" class="form-control datepicker"  placeholder="">
                        <label for="dateAll">
                        <input type="checkbox" {{($date == '' ?'checked':'')}} id="dateAll"> วันที่ทั้งหมด
                        </label>
                    </div>
                    </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-info" style="margin-top: 27px"><i class="fa fa-search"></i></button>
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
<input type="hidden" value="{{date('d-m-Y')}}" id="thisDate">
<input type="hidden" value="{{$album}}" id="album">
<input type="hidden" value="{{ url('backoffice/image_blog/data_table') }}" id="url_ckeditor">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $('#dateAll').click(function(){
       var date = $('#thisDate').val();
        if($(this).is(":checked")){
                $('#datepicker_').val("");
        }else{
                $('#datepicker_').val(date);
        }
    })
    $('#datepicker_').change(function(){
        var date = $(this).val();
        if(date != ''){
            $('#dateAll').removeAttr('checked')
        }
    });
    $(function() {
        $('.datepicker').datepicker({
            format:'dd-mm-yyyy',
            
        });
        
        var t = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#url_ckeditor').val(),
                type: "GET",
                data: {
                    album: $('#album').val(),
                    date: $('#datepicker_').val(),
                }
            },
            columns: [{
                    data: 'No',
                    name: 'No'
                },
                {
                    data: 'IMG',
                    name: 'IMG'
                },
                {
                    data: 'link',
                    name: 'link'
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
           
            stateSave: true

        });

        t.on('draw.dt', function() {
            var PageInfo = $('#table').DataTable().page.info();
            t.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });


        // setInterval(function() {
        //     var reloadCheck = $('#reloadCheck').val();
        //     if (reloadCheck > 0) {
        //         t.ajax.reload();
        //         $('#reloadCheck').val(0);
        //     }
        // }, 1300);


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

    function manageAlbum() {
        $.ajax({
            url: '/backoffice/image_blog/1',
            type: 'GET',
            success: function(data) {
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
            url: "image_blog/" + id,
            type: "POST",
            data: {
                _method: 'delete',
                _token: token
            },
            success: function(data) {
                $('#resultDelete').html(data);
                location.reload();

            }

        });
    }


    function deleteData(id) {
        alertify.confirm('!Delete Data', "Do you want delete this data?",
            function() {
                valDeleteData(id);
            },
            function() {
                alertify.error('cancel');
            });
    }

</script>
@endsection