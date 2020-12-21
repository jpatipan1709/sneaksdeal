<div class="modal" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form action="{{url('backoffice/saveSendVoucherTrue',$id)}}" method="POST">
            @csrf
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">จัดการรายการสินค้า</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
             {!! inputText('Tracking No.', 'trackingNo', '', '', 'md-12', 'required', '') !!}
            </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" >Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>
