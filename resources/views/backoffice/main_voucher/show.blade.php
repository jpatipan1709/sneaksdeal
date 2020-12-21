<div class="modal" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Show data Main Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <label style="font-size: 18px">{{ $data->name_main }}</label>
                <ul>
                    <li><b>รายละเอียด: </b>{!! $data->detail_main !!}</li>
                    <li><b>เวลาทำการ: </b>{{ $data->time_main }}</li>
                    <li><b>ที่อยู่: </b>{!! $data->address_main  !!}</li>
                    <li><b>เบอร์โทร: </b>{{ $data->tel_main }}</li>
                    <li><b>ราคา: </b>{{ $data->price_main }}</li>
                </ul>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
