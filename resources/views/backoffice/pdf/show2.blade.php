<div class="modal" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Voucher ที่ : {{ $data->voucher_id }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 text-right">
                        ชื่อ :
                    </div>
                    <div class="col-md-10">
                        {{-- {{ dd($data) }} --}}
                        {{ $data->name_voucher }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-right">
                        จำนวนเข้าพัก :
                    </div>
                    <div class="col-md-10">
                        {{ $data->qty_customer }} ท่าน
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-right">
                        จำนวน/คืน :
                    </div>
                    <div class="col-md-10">
                        {{ $data->qty_night }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-right">
                        เวลาเปิดขาย - ปิดขาย :
                    </div>
                    <div class="col-md-10">
                        @php
                            $datestart = date_create($data->date_open);
                            $dateclose = date_create($data->date_close);
                        @endphp
                        {{ date_format($datestart,"d/m/Y").' ถึง '.date_format($dateclose,"d/m/Y") }}
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
