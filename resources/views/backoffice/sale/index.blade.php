@extends("backoffice/layout/components")

@section('top1') Sales @endsection

@section('top2') Sales @endsection

@section('top3') Sales detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "select_sale";
?>

@section('contents')
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 ">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    
                   <div class="row">
                       <div class="col-md-6 text-left">
                            <h4><i class="ion ion-clipboard mr-1"></i> ยอดขาย ของ 
                            @php
                            if($system_admin->name_main != null){
                                echo $system_admin->name_main;
                            }else{
                                echo "-";
                            }
                            @endphp
                             
                            </h4>
                       </div>
                   </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <p> <b>ชื่อ</b>: {{ $system_admin->name_main }}</p>
  
                    </div>
                    <div class="row">
                        <p><b>รายละเอียด</b> : {!! $system_admin->detail_main !!}</p>
                    </div>
                    <div class="row">
                        <p><b>โทรศัพท์</b> : {{ $system_admin->tel_main }}</p>
                    </div>
                    <hr/>
                    <div class="row">
                        <h4>ยอดขายทั้งหมด</h4>
                        @if(count($order_details) != 0)
                        <div class="table-responsive">
                            <table class="table  table-bordered display responsive nowrap" id="myTable">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">#</th>
                                        <th>Voucher Name</th>
                                        <th width="15%">QTY</th>
                                        <th width="15%">Price / Per</th>
                                        <th width="15%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_sum = 0;
                                        $discount_sum = 0;
                                    @endphp
                                    @foreach($order_details as $key => $order_detail2)
                                    <tr>
                                        <th class="text-center">{{ ++$key  }}</th>
                                        <td>{{ $order_detail2->name_voucher }}</td>
                                        <td class="text-center">{{ $order_detail2->qty }}</td>
                                        <td class="text-center">{{ number_format($order_detail2->priceper ,2,".",",") }}</td>
                                        <td class="text-right">{{ number_format($order_detail2->total ,2,".",",") }}</td>
                                    </tr>
                                    @php
                                        $total_sum += $order_detail2->total;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-right"><b>รวม</b> </td>
                                        <td class="text-right">฿ 
                                            @php 
                                                if($total_sum != null || $total_sum != "" || $total_sum != 0){
                                                    echo number_format($total_sum ,2,".",",");
                                                }else{
                                                    echo number_format(0 ,2,".",",");
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>ส่วนลด</b></td>
                                        <td class="text-right">฿ 
                                            @php 
                                                if($sum_discount != null){
                                                    echo number_format($sum_discount ,2,".",",");
                                                }else{
                                                    echo number_format(0 ,2,".",",");
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>รวมสุทธิ</b></td>
                                        <td class="text-right">฿ 
                                            @php 
                                                if($order_detail->total_sales != null){
                                                    echo number_format($order_detail->total_sales - $sum_discount ,2,".",",");
                                                }else{
                                                    echo number_format(0 ,2,".",",");
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="col-md-12">
                            <h5 class="text-center">--------------------- ยังไม่มีการขาย ---------------------</h5>
                        </div>
                        
                        @endif
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                   
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>

@endsection


@section('script')
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <div id="resultDelete"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        });
    </script>
@endsection