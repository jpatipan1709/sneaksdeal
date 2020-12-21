<style>
    .table {
        width: 100%;
    }

    ul li {
        font-size: 18px;
    }
</style>

<div class="modal" id="modal-default" >
    <div class="modal-dialog" style="max-width: 90%!important;">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Bulk upload detail</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ชื่อโรงแรม</th>
                            <th>รายละเอียดโรงแรม</th>
                            <th>ที่อยู่เว็บไซต์ของโรงแรม</th>
                            <th>เวลาทำการ</th>
                            <th>ที่อยู่</th>
                            <th>เบอร์ติดต่อ</th>
                            <th>ราคาแสดงด้านล่าง</th>
                            <th>Link vdo</th>
                            <th>Voucher name</th>
                            <th>จองภายใน/จองภายนอก (in,out)</th>
                            <th>Link contact</th>
                            <th>Tel contact</th>
                            <th>สิ่งอำนวยความสะดวก</th>
                            <th>จำนวนผู้เข้าพักได้สูงสุด</th>
                            <th>วันเข้าพัก</th>
                            <th>เวลาเริ่มขาย</th>
                            <th>เวลาสิ้นสุด</th>
                            <th>จำนวน Voucher</th>
                            <th>ราคาก่อนลด</th>
                            <th>ราคาลด</th>
                            <th>ราคาขาย</th>
                            <th>title</th>
                            <th>เงื่อนไข</th>
                            <th>More Subject</th>
                            <th>More Detail</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($getData AS $v)
                        <tr>
                            <td>{{$v->name_main}}</td>
                            <td>{{$v->detail_main}}</td>
                            <td>{{$v->link_main}}</td>
                            <td>{{$v->time_main}}</td>
                            <td>{{$v->address_main}}</td>
                            <td>{{$v->tel_main}}</td>
                            <td>{{$v->price_main}}</td>
                            <td>{{$v->link_vdo}}</td>
                            <td>{{$v->name_voucher}}</td>
                            <td>{{$v->type_voucher}}</td>
                            <td>{{$v->link_voucher_contact}}</td>
                            <td>{{$v->tel_voucher_contact}}</td>
                            <td>{{$v->relation_facilityid}}</td>
                            <td>{{$v->qty_customer}}</td>
                            <td>{{$v->qty_night}}</td>
                            <td>{{$v->date_open}}</td>
                            <td>{{$v->date_close}}</td>
                            <td>{{$v->qty_voucher}}</td>
                            <td>{{$v->price_agent}}</td>
                            <td>{{$v->sale}}</td>
                            <td>{{$v->price_sale}}</td>
                            <td>{{$v->title_voucher}}</td>
                            <td>{{$v->term_voucher}}</td>
                            <td>{{$v->name_extra}}</td>
                            <td>{{$v->detail_extra}}</td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>


            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
