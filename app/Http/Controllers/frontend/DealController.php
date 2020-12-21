<?php


namespace App\Http\Controllers\frontend;

use App\Model\LogPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Voucher;
use App\Http\Controllers\Controller;
use App\Model\admin\BannerModel;
use App\Model\admin\VoucherModel;
use App\Model\admin\SystemFileModel;
use App\Model\admin\MainVoucherModel;
use App\Model\ClickVoucher;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\TypeVoucher;
use App\Model\Location;
use App\Orders;
use App\Order_voucher;
use Validator;
use DB;
use Redirect;
use Session;
use Datatables;
use Storage;
use Response;

class DealController extends Controller
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
        $result[] = $this->stringLen($val, 2);
        $text = [];
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
        $id_main = [];
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

    public function typeDeal(Request $request, $type)
    {
        $search = @$request->search;
        $getType = TypeVoucher::query()->where('name_type', $type)->first();

        $voucher = SelectVoucherModel::query()
            ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
            ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
            ->where('main_voucher.code_type', 'LIKE', "%" . $getType->code_type . "%")
            ->where('tb_voucher.status_voucher', 'show')
            ->where('select_voucher.main_join', '!=', 0)
          ->where('select_voucher.voucher_id_join', '!=',0);

//            ->whereIn('main_voucher.id_main', $results);
        if ($search != '') {
            $results = $this->search_detail($search);
            $voucher = $voucher->whereIn('main_voucher.id_main',$results);
        }
        $voucher = $voucher
//            ->where('main_voucher.name_main','Love Andaman')
//            ->orderByRaw('(100*tb_voucher.sale/tb_voucher.price_agent) DESC')
            ->orderByRaw('IF(TIMESTAMPDIFF(minute,NOW(),tb_voucher.date_close) < 0 ,999999999999999999,TIMESTAMPDIFF(minute,NOW(),tb_voucher.date_close))','ASC')
            ->paginate($this->perPage);

        return view('pages.deal', compact('voucher', 'type', 'search'));
//        $results = $this->search_detail($search);

    }

    public function location()
    {
        $data = Location::query()->orderBy('name_location', 'ASC')->get();
        $res = [];
        foreach ($data AS $v) {
            $res[] = $v->name_location;
        }
        return implode('|', $res);
    }
}
