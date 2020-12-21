<?php


namespace App\Http\Controllers\frontend;

use App\Model\LogPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Voucher;
use App\Http\Controllers\Controller;
use App\Model\BannerSingleJourney;
use App\Model\admin\VoucherModel;
use App\Model\admin\SystemFileModel;
use App\Model\admin\MainVoucherModel;
use App\Model\ClickVoucher;
use App\ClickVoucherAddCart;
use App\Model\admin\SelectVoucherModel;
use App\Orders;
use App\Order_voucher;
use App\Discount;
use App\Order_details;
use Validator;
use DB;
use Redirect;
use Session;
use Datatables;
use Storage;
use Response;

class SingleJourneyController extends Controller
{
    public $perPage = 10;

    public function index(Request $request)
    {
        //updateStatusCountdown
        $type_vouchers = DB::table("type_vouchers")->get();
        $banner = BannerSingleJourney::all();




            $voucher = MainVoucherModel::query()
                ->leftJoin('tb_voucher AS v', 'main_voucher.id_main',  'v.relation_mainid')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('v.status_voucher', 'show')
                ->where('main_voucher.code_type','LIKE','%S05%')
                ->whereNotNull('v.voucher_id')
                ->whereNull('v.deleted_at')
                ->paginate($this->perPage);


        return view('pages.singlejourney', compact('banner', 'voucher', 'type_vouchers'));
    }
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

    public function show($id)
    {
        //updateStatusCountdown
        $type_vouchers = DB::table("type_vouchers")->get();
        $banner = BannerSingleJourney::all();
        $search = $id;
        $results = $this->search_detail($search);
        $voucher = MainVoucherModel::query()
            ->leftJoin('tb_voucher AS v', 'main_voucher.id_main',  'v.relation_mainid')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
            ->where('v.status_voucher', 'show')
            ->whereIn('main_voucher.id_main',$results)
            ->where('main_voucher.code_type','LIKE','%S05%')
            ->whereNotNull('v.voucher_id')
            ->whereNull('v.deleted_at')
            ->paginate($this->perPage);

        return view('pages.singlejourney', compact('banner', 'voucher', 'type_vouchers','search'));
    }
}
