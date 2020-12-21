@extends("backoffice/layout/components")

@section('top1') Blog @endsection

@section('top2') home @endsection

@section('top3') Blog detail @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
    $active = "blog";
    $optionBLog = '<option value="">-กรุณาเลือก-</option>';

foreach($blog AS $val){
    $optionBLog .= '<option value="'.$val->id_blog.'">'.$val->name_blog.'</option>';

}
@endphp

@section('contents')

    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h4 class="pull-left">
                        <i class="ion ion-clipboard mr-1"></i>
                        เลือก Blog แสดงหน้าหลัก
                    </h4>

                    <div class="pull-right" style="float: right">
                        <a href="{{url('backoffice/blog')}}" class="btn btn-flat btn-primary"><i
                                    class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                    </div>
                </div>


                <!-- /.card-header -->
                <form name="frmCreate" id="frmCreate" method="post" action="{{url('backoffice/blog/select')}}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            {!!   inputSelect2('กรุณาเลือกข้อมูล Blog', 'blog', 'blog', '', 'lg-12', 'required', $optionBLog)!!}
                            <hr>
                            <div class="col-lg-12">
                                @if(@$data->name_blog != '')
                                <label>ที่แสดงตอนนี้ </label>
                                <br>
                                <ul>
                                    <li>{!! @$data->name_blog !!}</li>
                                </ul>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-info float-right"><i class="fa fa-plus"></i> Save
                            item
                        </button>
                    </div>
                </form>
            </div>


            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>

    <div id="resultCreate"></div>

@endsection

@section('script')


@endsection
