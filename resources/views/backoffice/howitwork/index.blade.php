@extends("backoffice/layout/components")

@section('top1') How It Work @endsection

@section('top2') home @endsection

@section('top3') How It Work detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "howitwork";
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
        <form name="frmCreate" id="frmCreate" method="post" action="{{url('backoffice/howitwork/create')}}" enctype="multipart/form-data">
        <div class="card" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h4 class="pull-left">
                    <i class="ion ion-clipboard mr-1"></i>
                    How It Work
                </h4>
                
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12">
                                @if(\Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
                                        <li>{{ \Session::get('success') }}</li>
                                    </div><br />
                                @endif
                                @php
                                    $howitworks = DB::table('tb_howitwork')->first();
                                    if($howitworks == null){
                                        $condition_value = old('detial');
                                    }else{
                                        $condition_value = $howitworks->hiw_detail;
                                    }
                                @endphp
                                {!! inputTextArea('รายละเอียด', 'detail', 'id-input', 'ckeditor', 'lg-12','required',$condition_value) !!}

                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

               
            </div>
            <!-- /.card-header -->
            <div class="card-body">

            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <button type="submit" class="btn btn-info float-right"><i class="fa fa-plus"></i> Add
                    item
                </button>
            </div>
        </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- right col -->
</div>

@endsection


@section('script')

@endsection