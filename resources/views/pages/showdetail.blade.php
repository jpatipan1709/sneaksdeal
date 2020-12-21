<div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
    
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">รายละเอียด ของ {{ $voucher->name_voucher }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
    
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 text-right">
                            ชื่อที่พัก :
                        </div>
                        <div class="col-md-10">
                            {{ $voucher->name_blog }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 text-right">
                            รายละเอียด :
                        </div>
                        <div class="col-md-10">
                            {{ $voucher->detail_blog }}
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
    