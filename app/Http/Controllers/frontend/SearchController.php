<?php

namespace App\Http\Controllers\frontend;

use App\Model\admin\MainVoucherModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\VoucherModel;
use App\Model\admin\SystemFileModel;
use App\Model\admin\BlogModel;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\TypeVoucher;
use App\Model\Location;
use Validator;
use DB;
use Datatables;
use Storage;

//use App\Model\admin\BannerModel;


class SearchController extends Controller
{
    public $perPage = 10;

    public function similar_in_array($sNeedle, $aHaystack)
    {
        foreach ($aHaystack as $sKey) {
            if (stripos(strtolower($sKey), strtolower($sNeedle)) !== false) {
                return true;
            }
        }
        return false;
    }

    public function stringLen($text, $qty)
    {
        $len = mb_strlen($text, 'UTF-8');
        $result = [];
        for ($i = 0; $i < $len; $i++) {
            $result[] = @mb_substr($text, $i, $qty, 'UTF-8');
        }
        return $result;
    }

    public function arrayStrLen($val)
    {

        $result[] = $this->stringLen($val, 8);
        $result[] = $this->stringLen($val, 7);
        $result[] = $this->stringLen($val, 5);
        $result[] = $this->stringLen($val, 6);
        $result[] = $this->stringLen($val, 4);
        $result[] = $this->stringLen($val, 3);
//        $result[] = $this->stringLen($val, 2);

        foreach ($result AS $array2) {

            foreach (array_filter($array2) AS $value) {
                if ($value != '' && mb_strlen(str_replace('"', '', $value)) > 2) {
                    $text[] = $value;
                }
            }
        }
        return $text;
    }

    public function search_detail($keyword)
    {
        $val = $keyword;
        $result = $this->arrayStrLen($val);
        $main = MainVoucherModel::query()->get();
        $txtCate = '';
        $txtStyle = '';
        $txtMaterial = '';
        foreach ($main AS $key => $v) {
            $detailProduct[$key][] = $v->id_main;
            $detailProduct[$key][] = $v->name_main;
            $detailProduct[$key][] = $v->detail_main;
            $detailProduct[$key][] = $v->address_main;

        }
        foreach ($detailProduct AS $idMain => $rb) {
            foreach ($result AS $rowString) {
                if (mb_strlen($rowString, 'UTF-8') > 0) {
                    $rString = $rowString;
                } else {
                    $rString = '';
                }

                if ($this->similar_in_array($rString, $rb)) {
                    $span[] = $rString;
                    $id_main[] = $rb[0];
                } else {
                    $id_main[] = null;
                    $span[] = '';
                }
            }
        }
        $idFilter = array_filter($id_main);
        $id_product = array_unique($idFilter);
        $countGroup = array_count_values($idFilter);
        if (count($idFilter) > 0) {
            $MaxSearch = array_keys($countGroup, max($countGroup));
        } else {
            $MaxSearch = [];
        }


//        foreach ()
        return $MaxSearch;
    }

    public function show(Request $request, $search)
    {

//        $voucher = SelectVoucherModel::query()
//            ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
//            ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//            ->orWhere('tb_voucher.title_voucher', 'like', '%' . $search . '%')
//            ->orWhere('tb_voucher.term_voucher', 'like', '%' . $search . '%')
//            ->orWhere('detail_main', 'like', '%' . $search . '%')
//            ->orWhere('address_main', 'like', '%' . $search . '%')
//            ->orWhere('name_main', 'like', '%' . $search . '%')
//            ->get();

//        $blog = BlogModel::where('type_blog', 'like', '%' . $search . '%')
//        ->orWhere('name_blog', 'like', '%' . $search . '%')
//        ->orWhere('title_blog', 'like', '%' . $search . '%')
//        ->orWhere('detail_blog', 'like', '%' . $search . '%')
//        ->orWhere('address_blog', 'like', '%' . $search . '%')
//            ->get();
//        $vouchers = SelectVoucherModel::query()
//            ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
//            ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//            ->get();
//        $idMain = [];
//        foreach ($vouchers AS $k => $v) {
//            $detail = [];
//            $detail[] = $v->title_voucher;
//            $detail[] = $v->term_voucher;
//            $detail[] = $v->detail_main;
//            $detail[] = $v->address_main;
//            $detail[] = $v->name_main;
//            $details = implode(' | ', $detail);
//            if (preg_match('/' . $search . '/', $details)) {
//                $idMain[] = $v->id_main;
//            }
//        }

        $results = $this->search_detail($search);
        $type = @$request->type;
        $voucher = SelectVoucherModel::query()
            ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
            ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
            ->whereIn('main_voucher.id_main', $results)
            ->where('select_voucher.voucher_id_join', '!=',0)
            ->where('select_voucher.main_join', '!=', 0)
            ->where('tb_voucher.status_voucher', 'show');
        if ($type != '' && $type != '0') {
            $voucher = $voucher->where('main_voucher.code_type', 'LIKE', "%" . $type . "%");
        }
        $voucher = $voucher
        ->orderByRaw('IF(TIMESTAMPDIFF(minute,NOW(),tb_voucher.date_close) < 0 ,999999999999999999,TIMESTAMPDIFF(minute,NOW(),tb_voucher.date_close))','ASC')
        ->paginate($this->perPage);
        $location = Location::query()->get();
        if($type != ''){
            $voucher->withPath('?type='.$type);
        }
//        if ($results == '') {
//            $voucher = [];
//        }
        $txtMenu = [];
        foreach ($location AS $row) {
            $txtMenu[] = $row->name_location;
        }
        if (in_array($search, $txtMenu)) {
            $activeStatus = 'true';
        } else {
            $activeStatus = 'false';

        }
        $typeVoucher = TypeVoucher::query()->where('type_show','multiple')->get();
        return view('pages.search', compact('search', 'voucher', 'activeStatus', 'typeVoucher', 'type'));
    }

    public function testSearch($search)
    {
        $countVoucher = \App\Orders::query()->select('tb_order.id', 'tb_order.status_order', 'detai.qty', 'detail.voucher_id')
            ->join('tb_order_detail AS detail', 'tb_order.id', 'detail.order_id')->where('tb_order.status_order', '000')->where('detail.voucher_id', 55)->sum('detail.qty');
        dd($countVoucher);
    }

}