<?php

namespace App\Http\Controllers\admin;

use App\Model\LogPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\TypeBlogModel;
use App\Model\ClickVoucher;
use Validator;
use DB;
use Datatables;
use Storage;
use Excel;
use DateTime;
//use App\Model\MainTestImport;
//use App\Model\VoucherTestImport;
use App\Model\LogImport;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\MainVoucherModel;
use App\Model\admin\VoucherModel;
use App\Model\admin\SystemFileModel;

class BulkUploadController extends Controller
{
    public function index()
    {
        $option = '';
        $voucher_ = '';
//        $object = new DateTime('16/12/2563');
//        $val = $object->format('Y-m-d H:i:s');
        return view('backoffice.bulk_upload.index', compact('option', 'voucher_'));

    }

    public function test()
    {
        echo phpinfo();
    }

    public function formatDate($date)
    {
//        dd($date);
        if($date != ''){
        $object = new DateTime($date);
        $val = $object->format('Y-m-d H:i:s');
        }else{
        $val = '';
        }
        return $val;
    }

    public function store(Request $request)
    {

        $getData = Excel::load($request->file('file'), function ($reader) {
            $reader->noHeading();
            $reader->toArray();
        })->get();
        $arrayMain = [];
        $arrayVoucher = [];

        foreach ($getData AS $rows => $column) {
            if ($rows >= 1 && $column[1] != '') {
                $type = $column[0];
                //main
                $name_main = $column[1];
                $detail_main = $column[2];
                $link_main = $column[3];
                $time_main = $column[4];
                $address_main = $column[5];
                $tel_main = $column[6];
                $price_main = $column[7];
                $link_vdo = $column[8];

                //voucher
                $name_voucher = $column[9];
                $type_voucher = $column[10];
                $link_voucher_contact = $column[11];
                $tel_voucher_contact = $column[12];
                $relation_facilityid = $column[13];
                $qty_customer = $column[14];
                $qty_night = $column[15];
                $date_open = $this->formatDate(str_replace('/','-',$column[16]));
                $date_close = $this->formatDate(str_replace('/','-',$column[17]));
                $qty_voucher = $column[18];
                $price_agent = $column[19];
                $sale = $column[20];
                $price_sale = $price_agent - ($sale == '' ? 0 : $sale);
                $title_voucher = $column[22];
                $term_voucher = $column[23];
                $name_extra = $column[24];
                $detail_extra = $column[25];
                $type_main = @str_replace(' ','',$column[26]);
                $replaceName = str_replace(' ', '', $name_main);
                if ($type == 'main_voucher') {
                    $checkName = MainVoucherModel::query()->whereRaw('REPLACE(name_main," ","") =   "' . $replaceName . '" ')->first();
                    if (!$checkName) {
                        $create = new MainVoucherModel();
                        $create->code_type = $type_main;
                        $create->name_main = $name_main;
                        $create->detail_main = $detail_main;
                        $create->link_main = $link_main;
                        $create->time_main = $time_main;
                        $create->address_main = $address_main;
                        $create->tel_main = $tel_main;
                        $create->price_main = $price_main;
                        $create->link_vdo = $link_vdo;
                        $create->save();
                        $idMain = $create->id_main;
                        $arrayMain[] = $idMain;
                    } else {
                        $idMain = $checkName->id_main;
                    }
                    $voucherCreate = new VoucherModel();
                    $voucherCreate->relation_mainid = $idMain;
                    $voucherCreate->name_voucher = $name_voucher;
                    $voucherCreate->type_voucher = $type_voucher;
                    $voucherCreate->link_voucher_contact = $link_voucher_contact;
                    $voucherCreate->tel_voucher_contact = $tel_voucher_contact;
                    $voucherCreate->relation_facilityid = $relation_facilityid;
                    $voucherCreate->qty_customer = $qty_customer;
                    $voucherCreate->qty_night = $qty_night;
                    $voucherCreate->date_open = $date_open;
                    $voucherCreate->date_close = $date_close;
                    $voucherCreate->qty_voucher = $qty_voucher;
                    $voucherCreate->price_agent = $price_agent;
                    $voucherCreate->sale = $sale;
                    $voucherCreate->price_sale = $price_sale;
                    $voucherCreate->title_voucher = nl2br($title_voucher);
                    $voucherCreate->term_voucher = nl2br($term_voucher);
                    $voucherCreate->name_extra = $name_extra;
                    $voucherCreate->detail_extra = nl2br($detail_extra);
                    $voucherCreate->stat_sale = 'y';
                    $voucherCreate->save();
                    $arrayVoucher[] = $voucherCreate->voucher_id;
                } else {
                    $checkName = MainVoucherModel::query()->whereRaw('REPLACE(name_main," ","") =   "' . $replaceName . '" ')->first();
                    if ($checkName) {
                        $voucherCreate = new VoucherModel();
                        $voucherCreate->relation_mainid = $checkName->id_main;
                        $voucherCreate->name_voucher = $name_voucher;
                        $voucherCreate->type_voucher = $type_voucher;
                        $voucherCreate->link_voucher_contact = $link_voucher_contact;
                        $voucherCreate->tel_voucher_contact = $tel_voucher_contact;
                        $voucherCreate->relation_facilityid = $relation_facilityid;
                        $voucherCreate->qty_customer = $qty_customer;
                        $voucherCreate->qty_night = $qty_night;
                        $voucherCreate->date_open = $date_open;
                        $voucherCreate->date_close = $date_close;
                        $voucherCreate->qty_voucher = $qty_voucher;
                        $voucherCreate->price_agent = $price_agent;
                        $voucherCreate->sale = $sale;
                        $voucherCreate->price_sale = $price_sale;
                        $voucherCreate->title_voucher = nl2br($title_voucher);
                        $voucherCreate->term_voucher = nl2br($term_voucher);
                        $voucherCreate->name_extra = $name_extra;
                        $voucherCreate->detail_extra = nl2br($detail_extra);
                        $voucherCreate->stat_sale = 'y';
                        $voucherCreate->save();
                        $arrayVoucher[] = $voucherCreate->voucher_id;

                    } else {
                        return $name_main . ' (ชื่อโรงแรมไม่ถูกต้องกรุณาตรวจสอบไฟล์ Excel อีกครั้ง)  <a href="' . url('backoffice/bulk_upload') . '">ย้อนกลับ</a>';
                    }

                }
            }
        }

        $logCreate = new LogImport();
        $logCreate->main_total = count($arrayMain);
        $logCreate->main_id_json = implode(',', $arrayMain);
        $logCreate->voucher_total = count($arrayVoucher);
        $logCreate->voucher_id_json = implode(',', $arrayVoucher);
        $logCreate->save();
        return redirect('backoffice/bulk_upload')->with('success', 'Upload success');


    }


    public function show($id)
    {
        $import = LogImport::query()->find($id);
        $getData = VoucherModel::query()
            ->leftJoin('main_voucher AS m', 'tb_voucher.relation_mainid', 'm.id_main')
            ->whereIn('tb_voucher.voucher_id', explode(',', $import->voucher_id_json))->get();
        return view('backoffice.bulk_upload.show', compact('getData'));
    }

    public function destroy($id)
    {
        $getData = LogImport::query()->find($id);
        $row = SystemFileModel::whereIn('relationId', explode(',', $getData->main_id_json))->where('relationTable', 'main')->get();
        foreach ($row AS $val) {
            Storage::delete('main/' . $val->name);
        }
        SystemFileModel::whereIn('relationId', explode(',', $getData->main_id_json))->where('relationTable', 'main')->delete();
        $getVoucher = VoucherModel::query()->select('voucher_id')->whereIn('relation_mainid', explode(',', $getData->main_id_json))->get();
        $idV = [];
        foreach ($getVoucher AS $v) {
            $idV[] = $v->voucher_id;
        }
        $rows = SystemFileModel::whereIn('relationId', $idV)->where('relationTable', 'voucher')->get();
        foreach ($rows AS $val) {
            Storage::delete('voucher/' . $val->name);
        }
        MainVoucherModel::query()->whereIn('id_main', explode(',', $getData->main_id_json))->delete();
        SelectVoucherModel::query()->whereIn('main_join', explode(',', $getData->main_id_json))->delete();
        VoucherModel::query()->whereIn('voucher_id', explode(',', $getData->voucher_id_json))->delete();
        LogImport::destroy($id);
    }

    public function queryDatatable()
    {
        $data = LogImport::query()->orderByDesc('id')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, true, false, true, 'backoffice/bulk_upload');
                return $Manage;
            })
            ->rawColumns(['No', 'Manage'])
            ->make(true);
    }
}