@extends("backoffice/layout/components")

@section('top1') Order @endsection

@section('top2') home @endsection

@section('top3') Order detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "order";
$active = "select_order";

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
                        แก้ไข Order
                    </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                                <h4>รายการสั่งซื้อที่ : {{ $data->id }}</h4>
                        </div>
                    </div>
                 
                    <hr/>
                    <div class="row">
                        <div class="col-md-2 text-right">
                            ชื่อ :
                        </div>
                        <div class="col-md-10">
                            {{-- {{ dd($data) }} --}}
                            {{ $data->name_member.' '.$data->lastname_member }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 text-right">
                            ที่อยู่ :
                        </div>
                        <div class="col-md-10">
                            {{ $data->address_member.'     ตำบล'.$data->d_name.'    อำเภอ'.$data->a_name.'    จังหวัด'.$data->p_name.'    '.$data->zip_code }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 text-right">
                            เบอร์โทรศัพท์ :
                        </div>
                        <div class="col-md-10">
                            {{ $data->tel_member }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 text-right">
                            อีเมล์ :
                        </div>
                        <div class="col-md-10">
                            {{ $data->email }}
                        </div>
                    </div>
                    <hr/>
                    <h4>รายการ Voucher</h4>
                    <div class="table-responsive">
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>ชื่อ Voucher</th>
                                    <th>รหัส Voucher</th>
                                    <th>จำนวน</th>
                                    <th>ราคา/ชิ้น</th>
                                    <th>สั่งซื้อวันที่</th>
                                    <th>สถานะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                              $order_details =   DB::table('tb_order_detail')
                                                    ->select('tb_order_detail.*','tb_order_detail.created_at as od_create')
                                                    ->where('order_id','=',$data->id)
                                                    ->get();
                                $i = 0;
                            @endphp
                            @foreach ($order_details as $KEY => $order_detail)
                            @php
                                 $voucher =   DB::table('tb_voucher')
                                                    ->where('voucher_id','=',$order_detail->voucher_id)
                                                    ->first();
               
                                $order_vouchers =   DB::table('order_vouchers')
                                                    ->where('order_detail_id','=',$order_detail->odt_id)
                                                    ->get();
                            @endphp 
                            
                                @foreach ($order_vouchers as $KEY2   =>  $order_voucher)
                                <tr class="text-center">
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $voucher->name_voucher }}</td>
                                        <td>{{ $order_voucher->code_voucher }}</td>
                                        <td>1</td>
                                        <td>{{ $order_detail->priceper.'  บาท' }}</td>
                                        <td>{{ $order_detail->od_create }}</td>
                                        <td> @if($order_voucher->stat_voucher == 'y') <span class="text-success">{{ 'ใช้งานแล้ว' }}</span> @else<span class="text-danger"> {{ 'ยังไม่ได้ใช้งาน' }}</span> @endif</td>
                                        <td>
                                            @php
                                             echo '<input type="hidden" name="email_send" id="email_send" value="'.$data->email.'"/>';
                                                if($order_voucher->stat_voucher == 'n'){
                                                    echo '<button class="btn btn-success show_modal" id="show_modal" atr="'.$order_voucher->order_voucher_id.'"  data-toggle="modal" data-target="#exampleModal">ใช้</button>';
                                                   
                                                }else{
                                                    echo '<button class="btn btn-success show_modal " id="show_modal" atr="'.$order_voucher->order_voucher_id.'"  data-toggle="modal" data-target="#exampleModal" disabled>ใช้</button>';
                                                }
                                            @endphp
                                            
                                        </td>
                                </tr>
                                @endforeach      
                            @endforeach
                            </tbody>
                        </table>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
    
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">รหัสยืนยัน</h4>
                 
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
    
                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="code_confirm" id="code_confirm" value=""/>
                    <input type="textbox" class="form-control" name="check_code_voucher" id="check_code_voucher" value=""/>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_vuocher">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
    
            </div>
        </div>
    </div>
    
@endsection
@section('script')
<input type="hidden" value="{{ csrf_token() }}" id="token">
<script>
$(".show_modal").click(function(){
    var code = $(this).attr('atr');
    $('#exampleModal').on('show.bs.modal', function(event){
		$('.modal-body #code_confirm').val(code);
	});	
});

$(".confirm_vuocher").click(function(){
    var id = $('.modal-body #code_confirm').val();
    var email_send = $("#email_send").val();
    var code = $('.modal-body #check_code_voucher').val();
    var token = $('#token').val();

    $.ajax({
        url: "{{ url('backoffice/order') }}/" + id,
            type: "POST",
            data: {_method: 'PUT', _token: token,id:id,code:code,email_send:email_send},
        success: function (data) {
            console.log(data);
            if(data == 1){
                alertify.success('บันทึกสำเร็จ การใช้งาน voucher เรียร้อย   ');
                setInterval(function(){ window.location.reload(); }, 3000);
                
            }else{
                alertify.error('เงื่อนไขการใช้ voucher ไม่ตรงตามเงื่อนไข');
            }
        }
    });
});
</script>

@endsection


