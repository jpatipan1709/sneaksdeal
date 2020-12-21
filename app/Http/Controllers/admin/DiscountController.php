<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Datatables;
use App\Discount;
use App\Model\DiscountVoucher;
use App\Model\admin\MainVoucherModel;
use App\Http\Requests\Checkdiscount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('tb_order')
            ->select('tb_order.*', 'tb_member.*', 'tb_order.created_at as m_create')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->get();

        $data = array(
            'orders' => $orders
        );

        return view('backoffice.discount.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $main_vouchers = DB::table('main_voucher')->where('deleted_at', '=', null)->orderBy('name_main','ASC')->get();

        $data = array(
            'main_vouchers' => $main_vouchers
        );


        return view('backoffice.discount.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function runCodeDiscount($id)
    {
        $n = 6;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString.$id;
    }

    public function store(Checkdiscount $request)
    {
        $code_discount = $request->code_discount;
        $qty = $request->qty;
        $discount_min = $request->discount_min;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $discount_bath = $request->discount_bath;
        $select_main = (@$request->checkAll == "all" ? 0 : @$request->select_main == '' ? 0 : $request->select_main);
        $type_code = $request->type_code;
        $partner_name = $request->partner_name;
        $discount_name = $request->discount_name;

        $discount = new Discount();
        $discount->discount_name = $discount_name;
        $discount->discount_code = $code_discount;
        $discount->discount_qty = $qty;
        $discount->discount_min = $discount_min;
        $discount->date_start = $date_start;
        $discount->date_end = $date_end;
        $discount->discount_bath = $discount_bath;
        $discount->discount_main = implode(',', $select_main);
        $discount->type_discount = $type_code;
        $discount->partner_name = $partner_name;
        $discount->save();
        $idInsert = $discount->discount_id;
        if ($type_code == 'multiple_code') {
            for ($i = 1; $i <= $qty; $i++) {
                $data = new DiscountVoucher();
                $data->ref_discount_id = $idInsert;
                $data->discount_code_multiple = $this->runCodeDiscount($idInsert);
                $data->save();
            }
        }

        return redirect(route('discount.index'))->with('success', 'เพิ่มข้อมูล ส่วนลดเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::query()->find($id);
        $code = DiscountVoucher::query()
            ->leftJoin('tb_member AS m', 'discount_vouchers.ref_user_id', 'm.id_member')
            ->where('ref_discount_id', $id)->get();
        return view('backoffice.discount.show', compact('discount', 'code','id'));
    }
   public function viewMain($id)
    {
        $discount = Discount::query()->find($id);
        $data = MainVoucherModel::query()->get();
        return view('backoffice.discount.view_main', compact('discount', 'data','id'));
    }

    public function importView(Request $request)
    {
        $id = @$request->id;

        $discount = Discount::query()->orderByDesc('discount_id')
            ->where('type_discount', 'multiple_code')
            ->get();
        $option = '<option value="0" >ทั้งหมด</option>';
        foreach ($discount AS $row) {
            $option .= '<option value="' . $row->discount_id . '" ' . ($row->discount_id == $id ? 'selected' : '') . '>code:' . $row->discount_code . ' name:' . $row->discount_name . ' (' . $row->created_at . ')</option>';
        }
        return view('backoffice.discount.import', compact('id', 'option'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tb_discount = Discount::query()
            ->where('discount_id', '=', $id)
            ->first();
        $mainId = explode(',', $tb_discount->discount_main);
        $main_vouchers = DB::table('main_voucher')->where('deleted_at', '=', null)->orderBy('name_main','ASC')->get();
        $data = array(
            'discount' => $tb_discount,
            'main_vouchers' => $main_vouchers
        );
        return view('backoffice.discount.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $select_main = (@$request->checkAll == "all" ? 0 : @$request->select_main == '' ? 0 : $request->select_main);


        $tb_discount = Discount::query()
            ->where('discount_id', '=', $id)
            ->update([
                'discount_code' => $request->code_discount,
                'discount_qty' => $request->qty,
                'discount_min' => $request->discount_min,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'discount_bath' => $request->discount_bath,
                'discount_main' => implode(',', $select_main),
                'updated_at' => now()

            ]);

        if ($tb_discount) {
            return redirect(route('discount.index'))->with('success', 'แก้ไขข้อมูล ส่วนลดเรียบร้อยแล้ว');
        } else {
            messageError('!Error Update');
        }
    }

    public function CheckCode(Request $request)
    {
        $code = $request->code;
        $check = Discount::query()
            ->leftJoin('discount_vouchers AS d', 'tb_discount.discount_id', 'd.ref_discount_id')
            ->where('tb_discount.discount_code', $code)->orWhere('d.discount_code_multiple', $code)->first();
        if ($check) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function AddCode(Request $request)
    {
        $code = $request->code;
        $idRef = $request->idRef;
        $data = new DiscountVoucher();
        $data->ref_discount_id = $idRef;
        $data->discount_code_multiple = $code;
        $data->save();
        $idNew = $data->id;
        Discount::query()->where('discount_id',$idRef)->update([
            'discount_qty'=> DB::raw('discount_qty +1')
        ]);
        return $idNew;
    }

    public function deleteCode($id)
    {
        $check = DiscountVoucher::query()->find($id);
        DiscountVoucher::destroy($id);
        Discount::query()->where('discount_id',$check->ref_discount_id)->update([
            'discount_qty'=> DB::raw('discount_qty -1')
        ]);
    }

    public function destroy($id)
    {
        DiscountVoucher::query()->where('ref_discount_id', $id);
        Discount::destroy($id);
        messageSuccess('Delete success');
    }

    public function destroySubCode($id)
    {

        DiscountVoucher::query()->where('ref_discount_id', $id);
        messageSuccess('Delete success');
    }

    public function queryDatatable()
    {
        $data = Discount::query()
            ->orderByDesc('discount_id')
            ->get();
        // dd($data);
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('main_hotel', function ($data) {
                if ($data->discount_main == 0) {
                    return '-';
                } else {
                    return '<button type="button" onclick="viewMain(' . $data->discount_id . ')" title="Code" href="" class="btn btn-flat btn-outline-info"><i><i class="far fa-eye"></i></i></button>';
                }

            })
            ->addColumn('Status', function ($data) {
                if ($data->type_discount == 'single_code') {
                    $text = '<span style="color:red">Single Code</span>';
                } else {
                    $text = '<span style="color:green">' . $data->partner_name . '</span>';

                }
                return $text;
            })
            ->addColumn('Code', function ($data) {
                if ($data->type_discount == 'single_code') {
                    $text = $data->discount_code;
                } else {
                    $text = '<button type="button" onclick="viewCode(' . $data->discount_id . ')" title="Code" href="" class="btn btn-flat btn-outline-info"><i><i class="far fa-eye"></i></i></button>';

                }
                return $text;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->discount_id, false, true, true, 'backoffice/discount');
                return $Manage;
            })
            ->rawColumns(['No', 'main_hotel', 'Status', 'Manage','Code'])
            ->make(true);
    }

    public function DataTableDiscount(Request $request, $id)
    {
        $data = Discount::query()
            ->leftjoin('main_voucher AS m', 'tb_discount.discount_main', '=', 'm.id_main')
            ->leftjoin('discount_vouchers AS dis', 'tb_discount.discount_id', '=', 'dis.ref_discount_id')
            ->where('tb_discount.type_discount', 'multiple_code')
            ->orderByDesc('tb_discount.discount_id');
        if ($id != 0) {
            $data = $data->where('tb_discount.discount_id', $id);
        }
        $data = $data->get();
        // dd($data);
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Code', function ($data) {
                if ($data->discount_code != '') {
                    $val = $data->discount_code;
                } else {
                    $val = $data->discount_code_multiple;
                }
                return $val;
            })
            ->addColumn('Status', function ($data) {
                if ($data->status_used == 'yes') {
                    $val = 'ใช้แล้ว';
                } else {
                    $val = 'ยังไม่ใช้';
                }
                return $val;
            })
            ->rawColumns(['No', 'Code','Status'])
            ->make(true);
    }

}
