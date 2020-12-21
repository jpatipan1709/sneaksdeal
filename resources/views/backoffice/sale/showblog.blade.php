@extends("backoffice/layout/components")

@section('top1') My Main Voucher @endsection

@section('top2') My Main Voucher @endsection

@section('top3') My Main Voucher detail @endsection

@section('title') Backoffice Sneakdeal @endsection
<?php
$active = "blog_detail";
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
                            <h4><i class="ion ion-clipboard mr-1"></i> Main Voucher </h4>
                       </div>
                   </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    
                    <h3>{!! $order_detail->name_main !!}</h3>
                    <p>{!! $order_detail->detail_main !!}</p>
                    <p>
                        ที่อยู่ :
                        @php
                            if($order_detail->address_main != ""){
                                echo $order_detail->address_main;
                            }else{
                                echo 'ไม่ได้ระบุ';
                            }
                        @endphp
                    </p>
                    <p>โทรศัพท์ : 
                            @php
                            if($order_detail->tel_main != ""){
                                echo $order_detail->tel_main;
                            }else{
                                echo 'ไม่ได้ระบุ';
                            }
                        @endphp
                    </p>
                  
                    <p>เวลาทำการ :  
                        @php
                            if($order_detail->time_main != ""){
                                echo $order_detail->time_main;
                            }else{
                                echo 'ไม่ได้ระบุ';
                            }
                        @endphp
                       
                    </p>
                    <p>
                        ราคาโดยประมาณ :
                        @php
                        if($order_detail->price_main  != ""){
                            echo $order_detail->price_main ;
                        }else{
                            echo 'ไม่ได้ระบุ';
                        }
                    @endphp
                    </p>
                    <hr>
                    <h4>Voucher ที่เกี่ยวกับ {!! $order_detail->name_main !!}</h4>
                    @php
                    $vouchers = DB::table('tb_voucher')
                                    ->where('relation_mainid','=',$order_detail->id_main)
                                    ->get();
                    @endphp
                    @if (count($vouchers) != 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover display responsive nowrap" id="myTable">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Voucher Name</th>
                                    <th>Open Sale</th>
                                    <th>Close Sale</th>
                                    <th>Price Agent</th>
                                    <th>Price Sale</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @foreach ($vouchers as $key => $voucher)
                                <tr class="text-center">
                                    <td>{{  ++$key }}</td>
                                    <td>{{  $voucher->name_voucher }}</td>
                                    <td>{{  $voucher->date_open }}</td>
                                    <td>{{  $voucher->date_close }}</td>
                                    <td>{{  $voucher->price_agent }}</td>
                                    <td>{{  $voucher->price_sale }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h5 class="text-center">ไม่มีข้อมูล Vuocher</h5>
                    @endif
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