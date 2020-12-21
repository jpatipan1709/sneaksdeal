@php $option = '<option value="0">-กรุณาเลือก Voucher-</option>'; @endphp
@foreach($voucher AS $value)
    @php $option .=  '<option value="'.$value->voucher_id.'" '.($data->voucher_id == $value->voucher_id ?'selected':'').'>'.$value->name_voucher .'</option>'; @endphp
@endforeach
<div class="modal" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <form name="frmSelectVoucher" id="frmSelectVoucher" method="post">

                <div class="modal-header">
                    <h4 class="modal-title">จัดการเลือกดีล </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    {{ method_field('PATCH') }}
                    <div class="row">
                        {!! inputSelect2('เลือกดีลแสดง', 'select_voucher', 'select_voucher', '', 'lg-12', 'required', $option) !!}
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <a href="javascript:;" onclick="modalSelect({{$data->id_main}})" class="btn btn-success">Save</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
            {{--submitform to update controller --}}
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        $('.select2').select2();

    });

</script>