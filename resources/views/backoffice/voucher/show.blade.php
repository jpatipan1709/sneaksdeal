<style>
    .table {
        width: 100%;
    }

    ul li {
        font-size: 18px;
    }
</style>
@php
$qtySum1 = 0;
$qtySum2 = 0;
$qtySum3 = 0;
@endphp
<div class="modal" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Order Product Name: ({{$data->name_voucher}})</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <h5>stock  <span style="color:red">{{$data->qty_voucher}}</span> voucher</h5>

                <div class="row">
                    <ul class="nav nav-tabs" role="tablist" style="width: 100%">
                        <li class="nav-item">
                            <a class="nav-link active" href="#pending" role="tab" data-toggle="tab">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#payment" role="tab" data-toggle="tab">Payment Success</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#cancel" role="tab" data-toggle="tab">Cancel</a>
                        </li>
                    </ul>
                </div>
                <!-- Tab panes -->
                <div class="row">
                    <div class="tab-content" style="width: 100%">
                        <div role="tabpanel" class="tab-pane  active" id="pending">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Orders Number</th>
                                    <th>QTY</th>
                                    <th>Member</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataPending AS $r)
                                    <tr>
                                        <td>{{sprintf('%011d',$r->id)}}</td>
                                        <td>{{$r->qty}}</td>
                                        <td>{{$r->name_member}}({{$r->email}})</td>
                                    </tr>
                                    @php $qtySum1 += $r->qty @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <th>รวม</th>
                                <th colspan="2">{{number_format($qtySum1)}}</th>
                                </tfoot>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="payment">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Orders Number</th>
                                    <th>QTY</th>
                                    <th>Member</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataSuccess AS $r)
                                    <tr>
                                        <td>{{sprintf('%011d',$r->id)}}</td>
                                        <td>{{$r->qty}}</td>
                                        <td>{{$r->name_member}}({{$r->email}})</td>
                                        @php $qtySum2 += $r->qty @endphp

                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <th>รวม</th>
                                <th colspan="2">{{number_format($qtySum2)}}</th>
                                </tfoot>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="cancel">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Orders Number</th>
                                    <th>QTY</th>
                                    <th>Member</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataCancel AS $r)
                                    <tr>
                                        <td>{{sprintf('%011d',$r->id)}}</td>
                                        <td>{{$r->qty}}</td>
                                        <td>{{$r->name_member}}({{$r->email}})</td>
                                    </tr>
                                    @php $qtySum3 += $r->qty @endphp

                                @endforeach
                                </tbody>
                                <tfoot>
                                <th>รวม</th>
                                <th colspan="2">{{number_format($qtySum3)}}</th>
                                </tfoot>
                            </table>
                        </div>
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
