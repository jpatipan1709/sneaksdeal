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
                <h4>รายการสั่งซื้อที่ : {{ $tb_order->o_id }}</h4>
                <hr/>
                <div class="row">
                    <div class="col-md-2 text-right">
                        ชื่อ :
                    </div>
                    <div class="col-md-10">
                        {{-- {{ dd($data) }} --}}
                        {{ $tb_order->name_member.' '.$tb_order->lastname_member }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-right">
                        ที่อยู่ :
                    </div>
                    <div class="col-md-10">
                        {{ $tb_order->address_member.'     '.$tb_order->d_name.'    '.$tb_order->a_name.'    '.$tb_order->p_name.'    '.$tb_order->zip_code }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-right">
                        เบอร์โทรศัพท์ :
                    </div>
                    <div class="col-md-10">
                        {{ $tb_order->tel_member }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-right">
                        อีเมล์ :
                    </div>
                    <div class="col-md-10">
                        {{ $tb_order->email }}
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
                                <th>จำนวน</th>
                                <th>ราคา/ชิ้น</th>
                                <th>ส่วนลด</th>
                               
                                <th>สั่งซื้อวันที่</th>
                                <th>รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                        
                          $order_details =   DB::table('tb_order_detail')
                                                ->select('tb_order_detail.*','tb_discount.*','tb_order_detail.created_at as od_create')
                                                ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
                                                ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')
                                                ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                                                ->leftjoin('tb_discount','tb_order_detail.discount','=','tb_discount.discount_id')
                                                ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                                                ->where('id_admin','=',Session::get('id_admin'))
                                                ->where('order_id','=',$tb_order->id)
                                                ->get();
       
                        $check_duscount = 0;
                        $sum_discount = 0 ;
                        $sum_total = 0 ;
                        @endphp
                        @foreach ($order_details as $KEY => $order_detail)
                            @php
                            $sum_total += $order_detail->total;
                                    if($order_detail->discount_main != 0 ){
                                        $sum_discount += $order_detail->discount_bath;
                                                
                                    }
                            @endphp
                                
                            
                        @php
                             $voucher =   DB::table('tb_voucher')
                                                ->where('voucher_id','=',$order_detail->voucher_id)
                                                ->first();
                        @endphp 
                            <tr class="text-center">
                                <td>{{ $KEY+1 }}</td>
                                <td>{{ $voucher->name_voucher }}</td>
                                <td>{{ $order_detail->qty }}</td>
                                <td>{{ $order_detail->priceper.'  บาท' }}</td>
                                <td>
                                        @if($order_detail->discount_main != 0)
                                            {{ $tb_order->discount_bath.'  บาท' }}
                                        @else 
                                            {{ '-' }}
                                        @endif
                                </td>
                               
                                <td>{{ $order_detail->od_create }}</td>
                                <td class="text-right">฿ {{ $order_detail->total.'  บาท' }}</td>
                            </tr> 
                        @php
                        ++$check_duscount;
                        @endphp
                        @endforeach
                         <tr class="text-right">
                                <td  colspan="6"><b>ยอดรวม</b> </td>
                                <td>
                                    ฿  {{ $sum_total }} บาท
                                </td>
                            </tr>
                            <tr class="text-right">
                                <td  colspan="6"><b>ส่วนลด</b></td>
                                <td>
                                    @if ($sum_discount == "" || $sum_discount == null )
                                          {{ '-' }} 
                                    @else 
                                        ฿  {{ $sum_discount }} บาท
                                    @endif
                                </td>
                            </tr>
                            <tr class="text-right">
                                    <td  colspan="6"><b>ยอดสุทธิ</b></td>
                                    <td>
                                        ฿  {{ $sum_total -  $sum_discount }} บาท
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
