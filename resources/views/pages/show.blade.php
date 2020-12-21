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
                    <h4>รายการสั่งซื้อที่ : </h4>
                    <hr/>
                  
                    <hr/>
                    <h4>รายการ Voucher</h4>
                    <table class="table table-hover  table-bordered display responsive nowrap" id="table2">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>ชื่อ Voucher</th>
                                    <th>จำนวน</th>
                                    <th>ราคา/ชิ้น</th>
                                    <th>ส่วนลด</th>
                                    <th>สั่งซื้อวันที่</th>
                                    <th>ยอดขาย</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                     $order_details = DB::table('tb_order_detail')
                                                    ->select('tb_order_detail.*','tb_voucher.*','main_voucher.*','system_admin.*','tb_order.*')
                                                    ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
                                                    ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')
                                                    ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                                                    ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                                                    ->where('tb_order_detail.order_id','=',$orders->id)
                                                    ->where('tb_order.status_order', '=', 000)
                                                    ->orWhere('tb_order.status_order', '=', 00)
                                                    ->whereNull('tb_order.deleted_at')
                                                    ->get();
                                @endphp 
                                @foreach ($order_details as $order_detail)
                                    <tr class="text-center">
                                        <td>#</td>
                                        <td>ชื่อ Voucher</td>
                                        <td>จำนวน</td>
                                        <td>ราคา/ชิ้น</td>
                                        <td>ส่วนลด</td>
                                        <td>สั่งซื้อวันที่</td>
                                        <td>ยอดขาย</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
    
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
    
            </div>
        </div>
    </div>
    