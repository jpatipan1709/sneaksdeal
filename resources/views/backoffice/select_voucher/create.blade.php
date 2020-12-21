@extends("backoffice/layout/components")

@section('top1') Select Voucher @endsection

@section('top2') home @endsection

@section('top3') Select detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "select_voucher";


?>

@section('contents')
<style>
    table {
        border-collapse: collapse;
        width: 400px;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    td {
        border-style: solid;

        background-color: #4CAF50;
        color: white;
    }
    .div-sort {
        border-style: solid;

        background-color: #4CAF50;
        color: white;
    }
</style>
<div class="row">
    <!-- Left col -->
    <section class="col-lg-12 ">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h4 class="pull-left">
                    <i class="ion ion-clipboard mr-1"></i>
                    เรียงลำดับแสดง </h4>

                <div class="pull-right" style="float: right">
                    <a href="{{ url('backoffice/select_voucher') }}" class="btn btn-flat btn-primary"><i
                                class=" fa fa-plus-square"> </i> ย้อนกลับ</a>
                </div>
            </div>


            <!-- /.card-header -->
            <form name="frmCreate" id="frmCreate" method="post" action="{{ url('/backoffice/select_voucher') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                   <div class="row" id="sortable">
                        @php
                        $i=1;
                        foreach ($data AS $val){
                        echo '
                        <div class="col-md-4 div-sort">
                          No.'.$i.' : <input type="hidden" name="sort[]" value="'.$val->main_join.'">'.$val->name_main.'
                        </div>
                        ';
                        $i++;
                       }
                        @endphp
                   </div>
<!--                    <table>-->
<!--                        <tbody id="sortable">-->
<!--                        $i=1;-->
<!--                        foreach ($data AS $val){-->
<!--                        echo '-->
<!--                        <tr>-->
<!--                            <td>No.'.$i.' : <input type="hidden" name="sort[]" value="'.$val->main_join.'">'.$val->name_main.'-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        ';-->
<!--                        $i++; }-->
<!--                        </tbody>-->
<!--                    </table>-->
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <button type="submit" class="btn btn-info float-right"><i class="far fa-edit"></i> Save
                        Sort by
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

<script>

    // function submit() {
    //     $.ajax({
    //         url: "../select_voucher",
    //         type: "POST",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         data: new FormData($('#frmCreate')[0]),
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function (data) {
    //             $('#resultCreate').html(data);
    //         }
    //     });
    // }

    $(function () {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
    });
</script>

@endsection
