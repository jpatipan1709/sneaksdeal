<div class="modal" id="modal-default">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
    
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">ดู</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
    
                <!-- Modal body -->
                <div class="modal-body">
                    <h4>รายการสั่งซื้อที่ : {{ $data->id }}</h4>
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
                            {{ $data->address_member.'     '.$data->d_name.'    '.$data->a_name.'    '.$data->p_name.'    '.$data->zip_code }}
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
                                        <th>รหัส Order</th>
                                        <th>รหัส Voucher</th>
                                        <th>ชื่อผู้ซื้อ</th>
                                        <th>ชื่อโรงแรม</th>
                                        <th>ราคา</th>
                                        <th>สถานะ Order (ชำระเงิน)</th>
                                        <th>วันที่ซื้อ</th>
                                        <th>วันที่ใช้งาน</th>
                                        <th>สถานะการใช้งาน</th>
                                        <th>สถานะการชำระให้โรงแรม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $order_details =   DB::table('tb_order_detail')
                                                        ->select('tb_order.*','tb_order_detail.*','tb_order_detail.created_at as od_create','tb_member.*','tb_order.created_at as m_create','tb_discount.*','order_vouchers.*','tb_member.*','main_voucher.*')
                                                        ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                                                        ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                                                        ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                                                        ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                                                        ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                                                        ->leftJoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
                                                        ->where('id_main', '=', Session::get('main_id_at'))
                                                        ->whereNull('tb_order.deleted_at')
                                                        ->where('tb_order_detail.order_id','=',$data->id)
                                                        ->get();
                                
                                @endphp
                                @foreach ($order_details as $KEY => $order_detail)
                                @php
                                    $voucher =   DB::table('tb_voucher')
                                                        ->where('voucher_id','=',$order_detail->voucher_id)
                                                        ->first();
                                    $pay_by =   DB::table('system_admin')->where('id_admin','=',$order_detail->pay_by)->first();
                                @endphp 
                                    <tr class="text-center">
                                        <td>{{ str_pad($data->id,6,"0",STR_PAD_LEFT) }}</td>
                                        <td>{{ $order_detail->code_voucher }}</td>
                                        <td>{{ $order_detail->name_member.' '.$order_detail->lastname_member }}</td>
                                        <td>{{ $order_detail->name_main }}</td>
                                        <td>{{ $order_detail->priceper }}</td>
                                        <td> @php
                                            
                                            if($order_detail->status_order == "000"){
                                                echo "<span class='text-success'>ชำระเงินแล้ว</span>";
                                            }else if($order_detail->status_order == "002"){
                                                echo "<span class='text-warning'>กำลังดำเนินการ</span>";
                                            }else{
                                                echo "<span class='text-danger'>ยกเลิก</span>";
                                            }
                                        @endphp</td>
                                        <td>{{ $order_detail->od_create }}</td>
                                        <td> 
                                            @if($order_detail->stat_voucher == "n")
                                                {{ "-"}}
                                            @else
                                                {{ $order_detail->use_date }}
                                            @endif
                                        </td>
                                        <td>@php
                                                if($order_detail->stat_voucher == "y"){
                                                    echo "<span class='text-success'>ใช้งานแล้ว</span>";
                                                }else{
                                                    echo "<span class='text-danger'>ยังไม่ได้ใช้งาน</span>";
                                                }
                                            @endphp</td>
                                        <td>
                                            @if ($order_detail->pay_status == 1)
                                                {!! "<span class='text-success'>ชำระแล้ว</span>"."<br/>" !!}
                                                @if($pay_by != "")
                                                    {{ 'โดย'.$pay_by->name_admin.''.$pay_by->lastname_admin }}
                                                @endif
                                            @else
                                                {!! "<span class='text-danger'>ยังไม่ได้ชำระ</span>" !!}
                                            @endif
                                        </td>
                                    </tr>         
                                @endforeach
                                    <tr class="text-right">
                                        <td  colspan="9"><b>ยอดรวม</b> </td>
                                        <td>
                                            ฿  {{ $sum_order->total_sales }} บาท
                                        </td>
                                    </tr>
                                    <tr class="text-right">
                                        <td  colspan="9"><b>ส่วนลด</b></td>
                                        <td>
                                            
                                            @if ($sum_order->order_discount == "" || $sum_order->order_discount == null )
                                                  {{ '-' }} 
                                            @else 
                                                ฿  {{ $sum_order->order_discount }} บาท
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="text-right">
                                            <td  colspan="9"><b>ยอดสุทธิ</b></td>
                                            <td>
                                                ฿  {{ $sum_order->total_sales -  $sum_order->order_discount }} บาท
                                            </td>
                                    </tr>   
                                </tbody>
                            </table>
                        </div>
                </div>
    
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
    
            </div>
        </div>
    </div>
    