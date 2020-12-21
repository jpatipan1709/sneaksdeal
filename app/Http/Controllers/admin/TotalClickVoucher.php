<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\TypeBlogModel;
use App\Model\admin\MainVoucherModel;
use App\Model\admin\VoucherModel;
use App\Model\ClickVoucher;
use Validator;
use DB;
use Datatables;
use Storage;

class TotalClickVoucher extends Controller
{
    public $getData = [];

    public function index(Request $request)
    {
//        $voucher_ = @$request->voucher_;
        $getForm = @$request->form_value;
        $date_all = @$request->date_all;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $main = @$request->main;
        $option = '<option value="">เลือกทั้งหมด</option>';
        $mainVoucher = MainVoucherModel::query()->get();
        foreach ($mainVoucher AS $v) {
            $option .= '<option value="' . $v->id_main . '" ' . ($main == $v->id_main ? 'selected' : '') . '>' . $v->name_main . '</option>';
        }
//        $voucher = VoucherModel::query()
//            ->select('tb_voucher.*', 'm.name_main')
//            ->join('main_voucher AS m', 'tb_voucher.relation_mainid', 'm.id_main')
//            ->get();
//        if ($voucher_ != '') {
//            $getVoucher = VoucherModel::find($voucher_);
//            $detail = ClickVoucher::query()->where('ref_id_voucher', $voucher_)->orderByDesc('id')->get();
//        } else {
//            $getVoucher = '';
//            $detail = '';
//        }

        return view('backoffice.click_voucher.index', compact('option', 'getVoucher', 'detail', 'getForm', 'date_all', 'date_start', 'date_end'));

    }

    public function edit(Request $request, $id)
    {
        $main = MainVoucherModel::query()->orderBy('name_main', 'ASC')->get();
        $voucherGuest = \App\Model\admin\VoucherModel::query()->where('relation_mainid', $id)->sum('total_click_voucher_guest');
        $voucherMember = \App\Model\admin\VoucherModel::query()->where('relation_mainid', $id)->sum('total_click_voucher_member');
        $option = '';
        foreach ($main AS $v) {
            $option .= '<option value="' . $v->id_main . '" ' . ($id == $v->id_main ? 'selected' : '') . '>' . $v->name_main . '  (' . $v->name_main . ')</option>';
        }

        $getForm = @$request->form_value;
        $date_all = @$request->date_all;
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        return view('backoffice.click_voucher.edit', compact('option', 'id', 'voucherGuest', 'voucherMember', 'date_end', 'date_start', 'date_all', 'getForm'));
    }

    public function listClick($id)
    {
        $voucher = VoucherModel::find($id);
        $voucherGuest = ClickVoucher::query()->where('ref_id_voucher', $id)->where('type_user', 'guest')->count();
        $voucherMember = ClickVoucher::query()->where('ref_id_voucher', $id)->where('type_user', 'member')->count();
        return view('backoffice.click_voucher.show', compact('voucher', 'id', 'voucherGuest', 'voucherMember'));
    }

    public function queryDatatable(Request $request)
    {
        $getForm = @$request->form_value;
        $date_all = @$request->date_all;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $main = @$request->main_voucher;
        $this->getData = ['date_all' => $date_all, 'date_start' => $date_start, 'date_end' => $date_end];
        $data = MainVoucherModel::query();
        if ($main != '') {
            $data = $data->where('id_main', $main);
        }
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Total', function ($data) {
                $all = $this->getData['date_all'];
                $date_start = $this->getData['date_start'];
                $date_end = $this->getData['date_end'];
                $voucher = ClickVoucher::query()
                    ->join('tb_voucher AS v', 'click_vouchers.ref_id_voucher', 'v.voucher_id');
                if ($all != 'all' && $date_start != '' && $date_end != '') {
                    $voucher = $voucher->whereBetween('click_vouchers.created_at', [@postDate($date_start), @postDate($date_end)]);
                }
                $voucher = $voucher->where('v.relation_mainid', $data->id_main)->count();
                $val = $voucher;
                return $val;
            })->addColumn('View', function ($data) {
                $val = '<a href="' . url('backoffice/total_click_voucher/' . $data->id_main . '/edit?date_all=all') . '" class="btn btn-info" title="ดูข้อมูล"><i class="fa fa-eye"></i></a>';
                return $val;
            })
            ->rawColumns(['No', 'Total', 'View'])
            ->make(true);
    }

    public function mainVoucherTable(Request $request)
    {
        $id = $request->id;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $date_all = $request->date_all;
        $this->getData = ['date_all' => $date_all, 'date_start' => $date_start, 'date_end' => $date_end];

        $data = VoucherModel::query()->where('relation_mainid', $id)->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('View', function ($data) {
                $val = '<a target="_blank" href="' . url('backoffice/total_click_voucher/' . $data->voucher_id . '/list') . '" class="btn btn-info" title="ดูข้อมูล"><i class="fa fa-eye"></i></a>';
                return $val;
            })
            ->addColumn('Total_Guest', function ($data) {
                $date_start = $this->getData['date_start'];
                $date_end = $this->getData['date_end'];
                $date_all = $this->getData['date_all'];
                $click = ClickVoucher::query()
                    ->where('ref_id_voucher', $data->voucher_id)
                    ->where('type_user', 'guest');
                if ($date_all != 'all' && $date_start != '' && $date_end != '') {
                    $click = $click->whereBetween('created_at', [@postDate($date_start), @postDate($date_end)]);
                }
                $val = $click->count();
                return $val;
            })
            ->addColumn('Total_Member', function ($data) {
                $date_start = $this->getData['date_start'];
                $date_end = $this->getData['date_end'];
                $date_all = $this->getData['date_all'];
                $click = ClickVoucher::query()
                    ->where('ref_id_voucher', $data->voucher_id)
                    ->where('type_user', 'member');
                if ($date_all != 'all') {
                    $click = $click->whereBetween('created_at', [$date_start, $date_end]);
                }
                $click = $click->count();
                return $click;
            })
            ->rawColumns(['No', 'View', 'Total_Guest', 'Total_Member'])
            ->make(true);
    }

    public function listVoucherTable(Request $request)
    {
        $id = $request->id;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $date_all = $request->date_all;
        $data = ClickVoucher::query()->where('ref_id_voucher', $id);
        if ($date_all != '') {
            $data = $data->whereBetween('created_at', [$date_start, $date_end]);
        }
        $data = $data->orderByDesc('created_at')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->rawColumns(['No'])
            ->make(true);
    }

    public function viewExport(Request $request)
    {

        $getForm = @$request->form_value;
        $date_all = @$request->date_all;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $main = @$request->main;
        $option = '<option value="">เลือกทั้งหมด</option>';
        $mainVoucher = MainVoucherModel::query()->get();
        foreach ($mainVoucher AS $v) {
            $option .= '<option value="' . $v->id_main . '" ' . ($main == $v->id_main ? 'selected' : '') . '>' . $v->name_main . '</option>';
        }
        return view('backoffice.click_voucher.export', compact('getForm', 'date_all', 'date_end', 'date_start', 'option', 'main'));
    }

    public function tableExport(Request $request)
    {
        ini_set('memory_limit', -1);

        $date_all = @$request->date_all;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $main_voucher = $request->main_voucher;
        $data = ClickVoucher::query()
            ->select('v.*', 'm.*', 'click_vouchers.*', 'click_vouchers.created_at AS created')
            ->join('tb_voucher AS v', 'click_vouchers.ref_id_voucher', 'v.voucher_id')
            ->join('main_voucher AS m', 'v.relation_mainid', 'm.id_main');
        if ($date_all != 'all') {
            $data = $data->whereBetween('click_vouchers.created_at', [@postDate($date_start), @postDate($date_end)]);
        }
        if ($main_voucher != '') {
            $data = $data->where('m.id_main', $main_voucher);
        }
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('DataClick', function ($data) {
                $val = $data->link_click . ' ' . ($data->link_click != '' ? ' , ' : '') . ' ' . $data->tel_click;
                return $val;
            })
            ->addColumn('Main', function ($data) {
                $val = @$data->name_main;
                return $val;
            })
            ->addColumn('Voucher', function ($data) {
                $val = @$data->name_voucher;
                return $val;
            })
            ->addColumn('Member', function ($data) {
                $val = ($data->type_user == 'member' ? 1 : 0);
                return $val;
            })
            ->addColumn('Guest', function ($data) {
                $val = ($data->type_user == 'guest' ? 1 : 0);
                return $val;
            })
            ->addColumn('Total', function ($data) {
                $val = 1;
                return $val;
            })
            ->addColumn('PriceAgent', function ($data) {
                $val = number_format(@json_decode($data->json_data)->price_agent, 2);
                return $val;
            })
            ->addColumn('Sale', function ($data) {
                $val = number_format((@json_decode($data->json_data)->price_sale * 100) / @json_decode($data->json_data)->price_agent, 2);
                return $val;
            })
            ->addColumn('PriceSale', function ($data) {
                $val = number_format(@json_decode($data->json_data)->price_sale, 2);
                return $val;
            })
            ->addColumn('TotalPrice', function ($data) {
                $val = number_format(@json_decode($data->json_data)->price_sale * 1, 2);

                return $val;
            })
            ->rawColumns(['No', 'DataClick', 'Member', 'Guest', 'Total', 'PriceAgent', 'Sale', 'PriceSale', 'TotalPrice', 'Voucher', 'Main'])
            ->make(true);
    }

}