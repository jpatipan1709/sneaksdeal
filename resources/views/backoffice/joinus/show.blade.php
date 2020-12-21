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
                <table class="table " >
                    <tr>
                        <td class="text-right" width="15%">ชื่อ :</td>
                        <td>{{ $joinus->ju_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">โรงแรม :</td>
                        <td>{{ $joinus->ju_hotel }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">เบอร์โทรศัพท์:</td>
                        <td>{{ $joinus->ju_tel }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">อีเมล์ :</td>
                        <td>{{ $joinus->ju_email }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">คอมเมนท์ :</td>
                        <td>{{ $joinus->ju_content }}</td>
                    </tr>
                </table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
