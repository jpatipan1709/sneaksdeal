<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Voucher;
use App\Model\MainVoucherModel;
use App\Model\admin\FacilitiesModel;

class VoucherController extends Controller
{
    public function index()
    {
        $data = Voucher::query()->select('tb_voucher.voucher_id','m.name_main','tb_voucher.name_voucher')
            ->join('main_voucher AS m', 'tb_voucher.relation_mainid', 'm.id_main')->get();

        $res['status'] = 'success';
        $res['message'] = 'get voucher success';
        $res['data'] = $data;

        return response()->json($res);


    }

    public function show($id)
    {
        $data = Voucher::query()
            ->join('main_voucher AS m', 'tb_voucher.relation_mainid', 'm.id_main')->where('tb_voucher.voucher_id',$id)->get();
        $res['status'] = 'success';
        $res['message'] = 'get voucher success';
        $res['data'] = $data;

        return response()->json($res);


    }
}
