<?php

namespace App\Http\Controllers\admin;

use App\Model\admin\VoucherModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\MainVoucherModel;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\LogSelectVoucher;


class SelectVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.select_voucher.index');
    }

    public function test()
    {
        echo \request()->ip();
//        $replace = str_replace("\n", ' ', $text);
//        $replace2 = str_replace("  ", ' ', $replace);
//        $idArray = explode(' ', $replace2);
//        foreach ($idArray AS $key => $v) {
//            $no = $key + 1;
//            SelectVoucherModel::query()->where('main_join', $v)->update(['sort_by_view' => $no]);
//        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = SelectVoucherModel::query()->join('main_voucher as b', 'select_voucher.main_join', 'b.id_main')
            ->join('tb_voucher as vo', 'select_voucher.voucher_id_join', 'vo.voucher_id')->orderBy('sort_by_view', 'ASC')->get();
        return view('backoffice.select_voucher.create', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//dd($request);

        $create = new LogSelectVoucher();
        $create->ip_update = \request()->ip();
        $create->main_id_json = implode(',',$request->sort);
        $create->save();
        $i = 1;
        foreach ($request->sort AS $a) {
            SelectVoucherModel::where('main_join', $a)
                ->update(['sort_by_view' => $i]);
            $i++;

        }
        return redirect(url('backoffice/select_voucher'))->with('success', 'จัดเรียงข้อมูล เรียบร้อยแล้ว');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = MainVoucherModel::query()->leftJoin('select_voucher as c', 'main_voucher.id_main', 'c.main_join')
            ->leftJoin('tb_voucher as vo', 'c.voucher_id_join', 'vo.voucher_id')->where('main_voucher.id_main', '=', $id)->first();
        $voucher = VoucherModel::where('relation_mainid', $id)->get();
        return view('backoffice.select_voucher.show', compact('data', 'voucher'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $voucher = request()->select_voucher;
        $data = SelectVoucherModel::updateOrCreate(
            ['main_join' => $id],
            ['main_join' => $id, 'voucher_id_join' => $voucher]
        );
        //
        $get = SelectVoucherModel::query()->selectRaw('max(sort_by_view) +1 AS MaxSort')->first();
        SelectVoucherModel::query()->where('main_join', $id)->update(['sort_by_view' => $get->MaxSort + 1]);
        VoucherModel::query()->where('voucher_id',$voucher)->update(['status_voucher'=>'show']);
        if ($data) {
            echo 'success';
        } else {
            echo 'error';
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }


    //index
    public function queryDatatable()
    {
        $data = MainVoucherModel::query()->leftJoin('select_voucher as c', 'main_voucher.id_main', 'c.main_join')
            ->leftJoin('tb_voucher as vo', 'c.voucher_id_join', 'vo.voucher_id')->get();
//        $data = BlogModel::with('SelectVoucher')->leftJoin(' aa ','tb_blog.id_blog','aa.blog_id_join');

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_main, true, false, false, 'backoffice/select_voucher');
                return $Manage;
            })
            ->rawColumns(['No', 'Manage'])
            ->make(true);
    }
}
