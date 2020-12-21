@extends("backoffice/layout/components")

@section('top1') Permission @endsection

@section('top2') home @endsection

@section('top3') Permission Admin  @endsection

@section('title') Backoffice Sneakdeal @endsection
@php
    $main = DB::table('tb_menu')->get();

$active = "permission";
$option = null;
foreach ($main AS $val){
$option .= '<option value="'.$val->id_menu.'" > '.$val->name_menu.'</option>';
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
                        แก้ไข ข้อมูล
                    </h4>
                </div>
                <form action="{{url('backoffice/permission') }}" method="post" id="frmUpdate"
                      enctype="multipart/form-data">
                    <input type="hidden" name="idAdmin" value="{{$data->id_admin}}">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12">
                                {!! inputSelect2('เมนู', 'menu', 'menu', '', 'lg-6', '', $option) !!}
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-info float-right"><i class="far fa-edit"></i>
                                        Save
                                        Save
                                    </button><br>
                                </div>
                            </div>
                            <div class="col-lg-12"><br>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">เมนูที่เลือก</th>
                                        <th scope="col">จัดการ</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @php
                                        $getMenu = DB::table('tb_permission')->leftJoin('tb_menu','tb_permission.menu_id','=','tb_menu.id_menu')->where('tb_permission.admin_id','=',$data->id_admin)->get();
                                    @endphp
                                    @foreach ($getMenu AS $rowMenuAdmin)
                                        <tr>
                                        <td> {{$rowMenuAdmin->name_menu}} </td>
                                        <td> <a href="javascript:void;" onclick="DeleteMenu({{$rowMenuAdmin->id_per}})" class="btn btn-danger">X</a> </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">

                    </div>
                </form>

            </div>
            <div id="resultUpdate"></div>
            <!-- /.card -->
        </section>

        <!-- right col -->
    </div>
    <input type="hidden" value="{{ csrf_token() }}" id="token">

@endsection
@section('script')
    <script>
    function DeleteMenu(id) {
        var token = $('#token').val();
        $.ajax({
            url: "{{ url('backoffice/permission') }}/" + id,
            type: "POST",
            data: {_method: 'delete', _token: token},
            success: function (data) {
       window.location = "{{ url('backoffice/permission/'.$data->id_admin.'/edit') }}";

            }

        });
    }

    </script>

@endsection


