@extends('layouts.components')

@section('contentFront')
<style>
    .scrollbarStyle::-webkit-scrollbar-track {
  box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}
.scrollbarStyle::-webkit-scrollbar {
  width: 0.5em;
}
 
.scrollbarStyle::-webkit-scrollbar-thumb {
  background-color: darkgrey;
  /* outline: 1px solid slategrey; */
}
.btn-primary{
    background-color: #3a96f9!important;
}
.btn-danger{
    background-color: #e25260!important;
}
</style>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="headtext top2rem">จัดการบัญชีของฉัน</div>
            </div>
            <div class="col-lg-4 col-md-5 top2rem">
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">ข้อมูลส่วนตัว</div>
                        <a href="{{ route('customeraccount.edit',Session::get('id_member')) }}?status=profile"
                           style="color:  #3D3D3D;">
                            <div class="minipricetext" style="color: #6b9cff;">แก้ไข</div>
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">ชื่อ-นามสกุล</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->name_member.'
                        '.$members->lastname_member }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">เบอร์โทรศัพท์</div>
                        </div>
                        @php
                            function phone_number_format($number) {
                            $number = preg_replace("/[^\d]/","",$number);
                            $length = strlen($number);
                            if($length == 10) {
                            $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
                            }
                            return $number;
                            }
                        @endphp
                        <div class="travelnormaltext" style="color:  #707070;">{{ phone_number_format($members->tel_member)
                        }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">อีเมล</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->email }}</div>
                    </li>
                </ul>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        style="background-color: #e0e6f5;">
                        <div class="miniheadtext" style="color:  #3D3D3D;">ข้อมูลที่อยู่</div>
                        <a href="{{ route('customeraccount.edit',Session::get('id_member')) }}"
                           style="color:  #3D3D3D;">
                            {{-- <div class="minipricetext" style="color: #6b9cff;">แก้ไข</div> --}}
                        </a>
                        <a href="{{ route('customeraccount.edit',Session::get('id_member')) }}?status=address"
                           style="color:  #3D3D3D;">
                            <div class="minipricetext" style="color: #6b9cff;">แก้ไข</div>
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">ที่อยู่</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->address_member }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">ตำบล/แขวง</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->districts_id }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">อำเภอ/เขต</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->amphures_id }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">จังหวัด</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->p_name }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <div class="travelnormaltext" style="color:  #707070;">รหัสไปรษณีย์</div>
                        </div>
                        <div class="travelnormaltext" style="color:  #707070;">{{ $members->zip_code }}</div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-8 col-md-7 top2rem">
                @if(\Session::has('success'))
                    <div class="alert alert-success">
                        <li>{{ \Session::get('success') }}</li>
                    </div>
                @endif
                <div class="miniheadtext top0rem" style="color:  #3D3D3D;">รายการสั่งซื้อล่าสุด</div>
                <br>
            
                <div class="table-responsive scrollbarStyle" style="max-height:570px">
                    <table class="table table-bordered table-striped table-hover" style="font-family: kanit;">
                        <thead style="background-color:#e0e6f5;color:black;">
                        <tr style="white-space: nowrap;" class="text-center">
                            <th class="">เลขที่สั่งซื้อ</th>
                            <th class="text-center">เวลที่ซื้อ</th>
                            <th class="text-center">ราคารวม</th>
                            <th class="text-center">ส่วนลด</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">ตัวเลือก</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- {{ dd($order_details) }} --}}
                        @foreach ($order_details as $key => $order_detail)
                            <tr style="white-space: nowrap;">
                                <td class="">{{ str_pad($order_detail->id,11,"0",STR_PAD_LEFT) }}</td>
                                <td class="text-center">{{ $order_detail->od_create }}</td>
                                <td class="text-center">{{ $order_detail->order_total }}</td>
                                <td class="text-center">
                                    @if ($order_detail->order_discount == null)
                                        {{ '-' }}
                                    @else
                                        {{ $order_detail->order_discount }}
                                    @endif</td>
                                <td class="text-center">
                                    @if($order_detail->status_order == 000)
                                        <span class="text-success"><strong>{{ 'ชำระเงินแล้ว' }}</strong></span>
                                    @elseif($order_detail->status_order == 001)
                                        <span class="text-warning"><strong>{{ 'รอการชำระเงิน' }}</strong></span>
                                    @elseif($order_detail->status_order == 002)
                                        <span class="text-warning"><strong>{{ 'ชำระเงินล้มเหลว' }}</strong></span>
                                    @else
                                        <span class="text-danger"><strong>{{ 'ยกเลิกคำสั่งซื้อ' }}</strong></span>
                                    @endif
                                </td>

                                <td class="text-center">
                                            @if($order_detail->status_order == '001' || $order_detail->status_order == '002')    
                                                <a
                                            href="{{ url('order-detail',($order_detail->comfirm_payment == '' ? 1 :$order_detail->comfirm_payment)).($order_detail->comfirm_payment == '' ? '?runId='.$order_detail->id.'&payment=true' :'') }}"
                                            class="btn btn-primary btn-sm" title="ชำระเงิน"><i
                                                class="fas fa-money"></i></a>
                                                      <a onclick="cacelOrder({{$order_detail->id}})"
                                            href="javascript:void(0)"
                                            class="btn btn-danger btn-sm" title="ยกเลิกคำสั่งซื้อ"><i
                                                class="fas fa-close"></i></a>
                                            @endif
                                                <a
                                            href="{{ url('order-detail',($order_detail->comfirm_payment == '' ? 1 :$order_detail->comfirm_payment)).($order_detail->comfirm_payment == '' ? '?runId='.$order_detail->id :'') }}"
                                            class="btn btn-info btn-sm" title="ดูรายละเอียด"><i
                                                class="fas fa-search"></i></a>
                                            
                                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        
            </div>
        </div>
    </div>
    <div id="resultModal"></div>
<form method="post" id="frmAccountCancel" action="{{url('customeraccount/cancel')}}">
    @csrf
</form>
@endsection
@section('scriptFront')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-147734154-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function cacelOrder(id){
            Swal.fire({
            title: 'Do you want to cancel order?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'OK',
            denyButtonText: "Don't save",
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Saved!', '', 'success')
            var url =   $('#frmAccountCancel').attr('action');
         $('#frmAccountCancel').attr('action', url+'/'+id);
          $('#frmAccountCancel').submit();
            } else if (result.isDenied) {
                Swal.fire('Changes are not cancel order', '', 'info')
            }
            })
     
        }

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-147734154-1');
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        $(document).ready(function () {

            $("#account").addClass("active");
        });
    </script>
@endsection