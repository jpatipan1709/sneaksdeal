@extends("backoffice/layout/components")

@section('top1') Deal @endsection

@section('top2') home @endsection

@section('top3') Deal detail @endsection

@section('title') Backoffice Sneekdeal @endsection
<?php
$active = "deal";
?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="ion ion-clipboard mr-1"></i>
                        แสดงข้อมูลสิ่งอำนวยความสะดวก
                    </h3>

                    <div class="card-tools">
                        <a href="facilities/create" class="btn btn-flat btn-primary"><i class=" fa fa-plus-square"> </i> เพิ่มข้อมูล</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <button type="button" class="btn btn-info float-right"><i class="fa fa-plus"></i> Add item</button>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>

@endsection