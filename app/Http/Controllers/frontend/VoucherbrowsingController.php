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

class VoucherbrowsingController extends Controller
{
    public $perPage = 10;

    public function index(Request $request)
    {
        //updateStatusCountdown
        VoucherModel::query()->where('date_open', '<=', date('Y-m-d H:i:s'))->where('status_countdown', 'post')->update(['status_countdown' => 'sale']);
        $orders =  Orders::query()->whereIn('status_order', ['001', '002'])->whereRaw("created_at <= NOW( ) - INTERVAL 24 HOUR")->get();
        foreach ($orders as $order) {
            if($order->order_discount > 0 && $order->refund_discount == 'no' && $order->discount_id != ''&& $order->discount_id != 0){
                Discount::query()->where('discount_id',$order->discount_id)->update(['discount_qty'=> DB::raw('discount_qty + 1')]);
                Orders::query()->where('id', $order->id)->update(['refund_discount' => 'yes', 'discount_id' => 0, 'discount_code_order' => NULL]);
            }
            $getData  = Order_details::query()->where('order_id', $order->id)->where('refund_stock','no')->get();
            foreach ($getData as $r) {
                Voucher::query()->where('voucher_id', $r->voucher_id)->update(['qty_voucher' => DB::raw('qty_voucher +' . $r->qty)]);
                Order_details::query()->where('odt_id', $r->odt_id)->update(['refund_stock' => 'yes']);
            }
        }
        Orders::query()->whereIn('status_order', ['001', '002'])->whereRaw("created_at <= NOW( ) - INTERVAL 24 HOUR")->update(['status_order' => '999']);


        $filter = DB::table("tb_filter")->where('stat_show', 'y')->get();
        $type_vouchers = DB::table("type_vouchers")->where('type_show','multiple')->get();
        $banner = BannerModel::all();


        $id = @$request->category;

        if ($id == 1 || $id == '') {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);

        } else if ($id == 2) {
            //ลกแรง
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderByRaw('(100*tb_voucher.sale/tb_voucher.price_agent) DESC')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->paginate($this->perPage);
        } else if ($id == 3) {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->where('tb_voucher.status_countdown', 'sale')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->orderBy('tb_voucher.voucher_id', 'DESC')
                ->paginate($this->perPage);
        } else if ($id == 4) {
//            $voucher = SelectVoucherModel::query()
//                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
//                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
//                ->where('select_voucher.main_join', '!=', 0)
//                ->where('tb_voucher.status_voucher', 'show')
//                ->orderBy('tb_voucher.date_close', 'ASC')
//                ->paginate($this->perPage);
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('tb_voucher.type_voucher', '=', 'in')
                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);
        } else if ($id == 5) {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('tb_voucher.type_voucher', '=', 'in')
                ->where('select_voucher.main_join', '!=', 0)

                //                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->orderByRaw('IF(TIMESTAMPDIFF(minute,NOW(),date_close) < 0 ,999999999999999999,TIMESTAMPDIFF(minute,NOW(),date_close))','ASC')
                ->paginate($this->perPage);
        }
        else if ($id == 6) {
//            $voucher = DB::table('hot_deal')->get();
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('tb_voucher.status_countdown', 'post')
                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);
        }else{
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->where('main_voucher.code_type', 'LIKE','%'.$id.'%')
                ->whereNotNull('tb_voucher.voucher_id')
                ->whereNull('tb_voucher.deleted_at')
                ->orderByRaw('(100*tb_voucher.sale/tb_voucher.price_agent) DESC')
                ->paginate($this->perPage);
        }

        return view('pages.voucherbrowsing', compact('banner', 'voucher', 'filter','type_vouchers','id'));
    }

    public function show($id)
    {
//dd($id);
        $voucher = DB::table("tb_voucher")
            ->leftJoin('main_voucher AS m', 'tb_voucher.relation_mainid', '=', 'm.id_main')
            ->where('tb_voucher.voucher_id', $id)
            ->where('tb_voucher.status_voucher', 'show')
            ->first();
        if ($voucher) {
            $voucherGroup = VoucherModel::query()
                ->select('tb_voucher.*','m.code_type')
                ->join('main_voucher AS m','tb_voucher.relation_mainid','m.id_main')
                ->where('tb_voucher.relation_mainid', $voucher->relation_mainid)
                ->where('tb_voucher.voucher_id', '!=', $id)
                ->where('tb_voucher.status_voucher', 'show')->get();
            $topClickAddCart = ClickVoucherAddCart::query()->selectRaw('count(ref_id_voucher) AS countVoucher,ref_id_voucher AS voucher_id')->groupBy('ref_id_voucher')->limit(20)->orderBy(DB::raw('count(ref_id_voucher)'),'DESC')->get();
            $topClick = ClickVoucher::query()->selectRaw('count(ref_id_voucher) AS countVoucher,ref_id_voucher AS voucher_id')->groupBy('ref_id_voucher')->limit(20)->orderBy(DB::raw('count(ref_id_voucher)'),'DESC')->get();
            $vId = [];
            foreach($topClickAddCart AS $v){
                $vId[] = $v->voucher_id;
            }
            foreach($topClick AS $v){
                $vId[] = $v->voucher_id;
            }
            $sorting_voucher = VoucherModel::query()
                ->leftJoin('main_voucher AS m', 'tb_voucher.relation_mainid', '=', 'm.id_main')
                ->whereIn('tb_voucher.voucher_id', $vId)
                ->where('tb_voucher.status_voucher', 'show')
                ->inRandomOrder()
                ->limit(8) 
                ->get();

//        $SystemFileModel = SystemFileModel::where('relationTable','=','voucher')->get();

            return view('pages.voucherdetail', compact('banner', 'voucher', 'voucherGroup', 'sorting_voucher', 'orders'));
        } else {
//            return Redirect::back()Redirect::back()->with('error','link voucher detail issue');
            return redirect('')->with('error', 'link voucher detail issue');
        }
    }

    public function checkCountDown(Request $request)
    {
        $id = $request->id;
        $v = VoucherModel::query()->find($id);
        if ($v->status_countdown == 'post') {
            if ($v->date_open <= date('Y-m-d H:i:s')) {
                VoucherModel::query()->where('voucher_id', $id)->update(['status_countdown' => 'sale']);
                return 'update';
            } else {
                return 'not';
            }
        } else {
            return 'not';
        }

    }

    public function clickVoucher(Request $request, $id)
    {
        $getVoucher = VoucherModel::query()->find($id);
        if (@Session::get('id_member') != '') {
            $type = 'member';
            $name = @Session::get('name_member') . ' ' . @Session::get('lastname_member');
            VoucherModel::query()->where('voucher_id', $id)->update(['total_click_voucher_member' => DB::raw('total_click_voucher_member + 1')]);
        } else {
            $type = 'guest';
            $name = 'guest' . time();
            VoucherModel::query()->where('voucher_id', $id)->update(['total_click_voucher_guest' => DB::raw('total_click_voucher_guest + 1')]);
        }
        $create = new ClickVoucher();
        $create->ref_id_voucher = $id;
        $create->full_name = $name;
        $create->email = @Session::get('email');
        $create->tel = @Session::get('tel_member');
        $create->type_user = $type;
        $create->tel_click = $getVoucher->tel_voucher_contact;
        $create->link_click = $getVoucher->link_voucher_contact;
        $create->json_data = json_encode($getVoucher);
        $create->save();
        return 'ok';
    }

    public function filter(Request $request, $id)
    {
        if ($id == 1) {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);

        } else if ($id == 2) {
            //ลกแรง
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderByRaw('(100*tb_voucher.sale/tb_voucher.price_agent) DESC')
                ->paginate($this->perPage);
        } else if ($id == 3) {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->where('tb_voucher.status_countdown', 'sale')
                ->orderBy('tb_voucher.voucher_id', 'DESC')
                ->paginate($this->perPage);
        } else if ($id == 4) {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderBy('tb_voucher.date_close', 'ASC')
                ->paginate($this->perPage);
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('tb_voucher.type_voucher', '=', 'in')
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);
        } else if ($id == 5) {
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('tb_voucher.type_voucher', '=', 'in')
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);
        }
        else if ($id == 6) {
//            $voucher = DB::table('hot_deal')->get();
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('tb_voucher.status_countdown', 'post')
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->orderBy('sort_by_view', 'asc')
                ->paginate($this->perPage);
        }else{
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
//                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->where('select_voucher.voucher_id_join', '!=', 0)
                ->where('tb_voucher.status_voucher', 'show')
                ->where('main_voucher.code_type', 'LIKE','%'.$id)
                ->orderByRaw('(100*tb_voucher.sale/tb_voucher.price_agent) DESC')
                ->paginate($this->perPage);
        }

        function addCol($count)
        {
            if ($count == 1) {
                echo '<div class="col" style="padding: 0;"></div>';
                echo '<div class="col" style="padding: 0;"></div>';
                echo '<div class="col" style="padding: 0;"></div>';
                echo '<div class="col" style="padding-left: 0;"></div>';
            } else if ($count == 2) {
                echo '<div class="col" style="padding: 0;"></div>';
                echo '<div class="col" style="padding: 0;"></div>';
                echo '<div class="col" style="padding-left: 0;"></div>';
            } else if ($count == 3) {
                echo '<div class="col" style="padding: 0;"></div>';
                echo '<div class="col" style="padding-left: 0;"></div>';
            } else if ($count == 4) {
                echo '<div class="col" style="padding-left: 0;"></div>';
            } else {
                echo '';
            }


        }
//$voucher->withPath('voucherbrowsing/'.$id);
        $noVWhere = [];

        foreach ($voucher AS $key => $rowVoucher) {
            $count_voucher = DB::table('tb_order_detail')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->where('tb_order_detail.voucher_id', '=', $rowVoucher->voucher_id)
                ->get();
            if ($rowVoucher->voucher_id != '' && ($rowVoucher->deleted_at == '' || $rowVoucher->deleted_at == null)) {
                $Datetest = date_format(date_create($rowVoucher->date_close), "F j, Y H:i:s");

                $file = DB::table('system_file')
                    ->where('relationId', '=', $rowVoucher->id_main)
                    ->where('relationTable', '=', 'main')
                    ->offset(1)
                    ->limit(100)
                    ->orderBy('sort_img', 'asc')
                    ->get();

                $file2 = DB::table('system_file')
                    ->where('relationId', '=', $rowVoucher->id_main)
                    ->where('relationTable', '=', 'main')
                    ->orderBy('sort_img', 'asc')
                    ->first();


                $percen = salePercen($rowVoucher->price_agent, $rowVoucher->sale);
                $dateExpired = strtotime($rowVoucher->date_close);
                $thisTime = time();
                $totalExpired = time() - $dateExpired;
                $dayFive = 86400 * 5;
                if (($dayFive >= $totalExpired && $rowVoucher->expired != 'y') || ($dayFive <= $totalExpired && $rowVoucher->expired == 'y') || ($dayFive >= $totalExpired)) {
                    $noV = 1;
                    echo '  <div class="top2rem"></div>
                    <div class="row box">
                        <div class="col-lg-5 col-md-6 col-12" style="padding:  0px;">
                            <div class="saletext">' . $percen . '% OFF TODAY</div>';
                    if ($rowVoucher->type_voucher == 'in') {
                        echo '<div class="type-text" > <img src="' . url('img/LOGO_SNEAKDEAL2.png') . '" ></div >';
                    }
                    if (@$file2->name != null) {
                        echo '<a data-fancybox="gallery' . $key . '" href="' . url('storage/main/' . $file2->name) . '"><div class="controlimgboxvoucherbig">';
                        echo '<img class="imgbox03" src="' . url('storage/main/' . $file2->name) . '"></div></a>';
                    } else {
                        echo '<a data-fancybox="gallery' . $key . '" href="' . url('img/voucherbrowsing/img23.png') . '"><div class="controlimgboxvoucherbig">';
                        echo '<img class="imgbox03 " src="' . url('img/voucherbrowsing/img23.png') . '"></div></a>';
                    }

                    echo '<div class="row">';
                    foreach ($file AS $rowFile) {


                        echo '<div class="col"
                                         style="' . ($noV == 1 ? 'padding-right: 0;' : (5 === $noV ? 'padding-left:0;' : 'padding: 0;')) . ($noV > 5 ? 'display:none;' : '') . '">
                                        <a data-fancybox="gallery' . $key . '" href="' . url('storage/main/' . $rowFile->name) . '">
                                            <div class="controlimgboxvoucher">
                                                <img class="imgbox" src="' . url('storage/main/' . $rowFile->name) . '">
                                            </div>
                                        </a>
                                    </div>';
                        $noV++;
                    }
                    addCol(count($file));
                    if ($rowVoucher->status_countdown == 'post') {
                        $dateEndCountdown = date_format(date_create($rowVoucher->date_open), "F j, Y H:i:s");
                    } else {
                        $dateEndCountdown = date_format(date_create($rowVoucher->date_close), "F j, Y H:i:s");
                    }
                    echo '</div>
                        </div>
                        <script>countdownTime(\'' . $dateEndCountdown . '\', \'' . $rowVoucher->voucher_id . '\',\'' . $rowVoucher->status_countdown . '\');</script>
                        <div class="col-lg-7 col-md-6 col-12">
                            <a href="voucherdetail/' . $rowVoucher->voucher_id . '">                           
                                  <div class="row">
                                <div class="col-lg-8 col-md-7 col-12">
                                <div class="headtext top1rem">' . $rowVoucher->name_main . '</div>
                                </div>
                                </div>
                                <div class="daylifetext">
                                    <span id="timeCountdownWaiting' . $rowVoucher->voucher_id . '">เปิดขายในอีก <br></span>
                                    <span id="timeCountdownD' . $rowVoucher->voucher_id . '"></span>
                                    <span id="timeCountdownT' . $rowVoucher->voucher_id . '">Days</span>
                                    <span id="timeCountdownH' . $rowVoucher->voucher_id . '"></span>

                                </div><br>
                                <div class="detialtraveltext top1rem">' . mb_substr($rowVoucher->detail_main, 0, 280, 'UTF-8') . '...
                                </div>
                                <div class="travelnormaltext top1rem">' . $rowVoucher->address_main . '</div>

                                <div class="row">
                                    <div class="offset-md-6  col-md-6">
                                        <div class="pricetext text-right ">เริ่มต้นที่</div>
                                        <div class="bahttext text-right top-0rem"
                                             style="text-decoration: line-through;color: #707070;">
                                                 ฿ ' . number_format($rowVoucher->price_agent) . '
                                        </div>
                                        <div class="bahttext text-right top-0rem" style="color: red;font-weight: 600;">
        ฿ ' . number_format($rowVoucher->price_sale) . '
                                        </div>
                                        <div class="detialtraveltext text-right">' . $rowVoucher->qty_night . '</div>
                                    </div>
                                </div>

                                <a class="btn btn-md btnsneakout"
                                   href="' . url("/voucherdetail", $rowVoucher->voucher_id) . '"
                                  role="button">
                                       ดูรายละเอียดเพิ่มเติม</a>';

//                    $count_voucher = DB::table('hot_deal')->select('qty_sale', 'main_join')
//                        ->where('main_join', '=', $rowVoucher->relation_mainid)
//                        ->first();
//แก้ 29-01-63
                    $count_vouchers = Order_voucher::leftjoin('tb_order', 'order_vouchers.orders_id', '=', 'tb_order.id')
                        ->leftjoin('tb_order_detail', 'order_vouchers.order_detail_id', '=', 'tb_order_detail.odt_id')
                        ->where('main_id', '=', $rowVoucher->relation_mainid)
                        // ->where('status_order', '=', '000')
                        ->where(function ($query) {
                            $query->where('status_order', '=', '000')
                                ->orWhere('status_order', '=', '001');
                        })
                        ->count();

//                    $qtyText = $count_voucher->qty_sale;
//แก้ 29-01-63

                    echo '<div class="detialtraveltext" style=" position:  absolute;bottom: 20px;color:red;">';
                    if ($rowVoucher->show_sale == 'y') {
                        echo 'ขายไปแล้ว ' . $count_vouchers . '
                                    ดีล';
                    }
                    echo '</div>';


                    echo '<div class="bottravel"></div>
                            </a>
                        </div>

                    </div>';

                }
            }
        }
        echo ' <div class="row top2rem">
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end pagination-voucher" style="margin: 0;float: right">
                           '. $voucher->links() .'
                        </ul>
                    </nav>
                </div>
            </div>';
    }

    public function autocompleteampuhers(Request $request)
    {

        $term = Input::get('term');

        $results = array();

        $queries = DB::table('amphures')
            ->where('name_th', 'LIKE', '%' . $term . '%')
            ->get();

        foreach ($queries as $query) {
            $results[] = ['value' => $query->name_th];
        }

        return Response::json($results);

    }

    public function autocompletedistricts(Request $request)
    {

        $term = Input::get('term');

        $results = array();

        $queries = DB::table('districts')
            ->where('name_th', 'LIKE', '%' . $term . '%')
            ->get();

        foreach ($queries as $query) {
            $results[] = ['value' => $query->name_th];
        }

        return Response::json($results);

    }

}
