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
                    <form action="{{ route('travelguidemanage.update',$travel->tg_id) }}" method="post" id="frmUpdate">
                        @csrf                
                        {{ method_field('PATCH') }}                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="travel_name">Travel Guide Tag</label>
                                <input type="textbox" class="form-control" id="travel_name" name="travel_name" placeholder="ชื่อ Travel Guide Tag" value="{{ $travel->tg_name }}" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="travel_name">สถานะการใช้งาน</label>
                               <select name="tg_status" id="tg_status" class="form-control">
                                   <option value="1" @if($travel->tg_status == 1) {{ 'selected' }} @endif>ใช้งาน</option>
                                   <option value="0" @if($travel->tg_status == 0) {{ 'selected' }} @endif>ไม่ใช้งาน</option>
                               </select>
                            </div>
                        </div>   
                        <hr>
                        <h5>เลือก Blog ที่จะแสดงใช้ หัวข้อ <u>{{ $travel->tg_name }}</u></h5>   @if ($errors->has('blogid'))
                                    <span class="text-danger text-size-error" role="alert">
                                        {{ $errors->first('blogid') }}
                                    </span>
                                @endif
                        <hr>
                        <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                                <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>ชื่อ Blog</th>
                                    <th>
                                        <center>จัดการ</center>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $blog_id = explode(',',$travel->tg_blog_id);  
                                    $i = 0;
                                @endphp
                                @foreach ($blog as  $key => $row)
                                    <tr class="text-center">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $row->name_blog }}</td>
                                        <td>
                                          
                                        <input type="checkbox" name="blogid[]" id="blogid[]"  value="{{  $row->id_blog  }}" 
                                            @foreach ($blog_id as $key2 => $item)
                                                @if(isset($blog_id[$key2]))  
                                                    @if($blog_id[$key2] == $row->id_blog) 
                                                        {{ 'checked' }}
                                                    @endif 
                                                @endif     
                                            @endforeach    
                                        />
                                        </td>
                                    </tr>
                                    @php
                                    
                                    $i++;
                                    @endphp
                                 @endforeach
                                
                                </tbody>
                            </table>   
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