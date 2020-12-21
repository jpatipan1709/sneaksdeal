<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orders;
use App\Voucher;
use DB;

class DashboardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $voucher = DB::table('tb_voucher')->count(DB::raw('DISTINCT voucher_id'));
        $blog = DB::table('tb_blog')->count(DB::raw('DISTINCT id_blog'));
        $order = DB::table('tb_order')->count(DB::raw('DISTINCT id'));
        $member = DB::table('tb_member')->count(DB::raw('DISTINCT id_member'));
        //        $month1 = DB::table('tb_order')
        //                                ->leftjoin('tb_order_detail','tb_order.id','=','tb_order_detail.order_id')
        //                                ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
        //                                ->where('tb_order.status_order','=',000)
        //                                ->where(DB::raw('MONTH(tb_order.created_at)-1 = MONTH(CURDATE())-1'))
        //                                ->first();


        $orders = DB::table('tb_order')
            ->leftjoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->orderBy('tb_order.id', 'desc')
        
            ->limit(10)
            ->get();

        $order_group = DB::table('tb_order')
            ->select('discount_id', DB::raw('count(discount_id) as discount_count'))
            ->groupBy('discount_id')
            ->limit(10)
            ->get();
        // Sales Voucher Top 10
        $voucher_charts = Voucher::query()
            ->select('main_voucher.name_main', 'tb_order.created_at', 'tb_order_detail.qty', 'tb_voucher.name_voucher', 'tb_voucher.price_sale', 'tb_voucher.created_at', DB::raw('sum(tb_order_detail.qty) as sum_voucher'), DB::raw('sum(tb_order_detail.total) as sum_total'))
            ->leftjoin('tb_order_detail', 'tb_voucher.voucher_id', '=', 'tb_order_detail.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->groupBy('tb_order_detail.voucher_id')
            ->orderBy('sum_voucher', 'desc')
            ->where('status_order', '=', 000);
        $getFilterSaleVoucher = @$request->sales5;
        $dateThisDay = date('Y-m-d');
        $thisYear = date('Y');
        if ($getFilterSaleVoucher == 'D') {
            $voucher_charts = $voucher_charts->whereRaw("tb_order.created_at = '" . $dateThisDay . "'");
        } else if ($getFilterSaleVoucher == 'W') {
            $voucher_charts = $voucher_charts->whereRaw("tb_order.created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
        } else if ($getFilterSaleVoucher == 'M') {
            $voucher_charts = $voucher_charts->whereRaw("tb_order.created_at BETWEEN  (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH))  ");
        } else if ($getFilterSaleVoucher == 'Y') {
            $voucher_charts = $voucher_charts->whereRaw("YEAR(tb_order.created_at) = '" . $thisYear . "' ");
        }
        $voucher_charts = $voucher_charts->limit(10)->get();
        //กราฟ รูปภาพที่ 1
        $grapTotalPriceDiscount = [];
        $grapTotalPrice = [];
        $grapSales = @$request->grapSales;
        $discount_details = DB::table('tb_order')
        ->select('tb_order_detail.*', 'tb_order.*', 'tb_discount.*', DB::raw('SUM(tb_order.order_discount) as total_discount'))
        ->leftjoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
        ->leftjoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
        ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
        ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
        ->whereNull('tb_order.deleted_at')
        ->where('tb_order.status_order', '=', 000)
        ->groupBy('id');
        $order_detail = DB::table('tb_order')
            ->leftjoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
            ->where('status_order', '=', 000);
            if($grapSales == 'Y'){
            $order_detail = $order_detail->whereRaw("YEAR(tb_order.created_at) = '" . $thisYear . "' ");
            $discount_details = $discount_details->whereRaw("YEAR(tb_order.created_at) = '" . $thisYear . "' ");
            for($i = 1; $i <= 12;$i++){
                $grapTotalPrice[] = Orders::selectRaw("SUM(order_total - order_discount) as total_sales")->whereRaw("YEAR(created_at) = " . date('Y') . " AND MONTH(created_at) = ".$i."  AND status_order = '000'")->first()->total_sales;
                $grapTotalPriceDiscount[] = Orders::selectRaw("SUM(order_discount) as total_discount")->whereRaw("YEAR(created_at) = " . date('Y') . " AND MONTH(created_at) = " . $i . "  AND status_order = '000'")->first()->total_discount;
            }

            }else if($grapSales == 'D'){
            $order_detail = $order_detail->whereRaw("DATE(tb_order.created_at) = '". date('Y-m-d')."'");
            $discount_details = $discount_details->whereRaw("DATE(tb_order.created_at) = '" . date('Y-m-d') . "'");
            $j = 0;
            for ($i = 1; $i <= 12; $i++) {
                $runTimeEnd = (1 + $j);
                $runTimeStart =$j;
                $grapTotalPrice[] = Orders::selectRaw("SUM(order_total - order_discount) as total_sales")->whereRaw("DATE(created_at) = '". date('Y-m-d'). "' AND (TIME(created_at) BETWEEN '". $runTimeStart.":00:00' AND '". $runTimeEnd.":59:59' ) AND status_order = '000'")->first()->total_sales;
                $grapTotalPriceDiscount[] = Orders::selectRaw("SUM(order_discount) as total_discount")->whereRaw("DATE(created_at) = '" . date('Y-m-d') ."' AND (TIME(created_at) BETWEEN '".$runTimeStart.":00:00' AND '" . $runTimeEnd . ":59:59' ) AND status_order = '000'")->first()->total_discount;
            $j+=2;}
            }
           else if(mb_strlen($grapSales) > 3){
            $order_detail = $order_detail->whereRaw("DATE(tb_order.created_at) = '" . postDate($grapSales) . "'");
            $discount_details = $discount_details->whereRaw("DATE(tb_order.created_at) = '" . postDate($grapSales) . "'");
            $j = 0;
            for ($i = 1; $i <= 12; $i++) {
                $runTimeEnd = (1 + $j);
                $runTimeStart = $j;
                $grapTotalPrice[] = Orders::selectRaw("SUM(order_total - order_discount) as total_sales")->whereRaw("DATE(created_at) = '" . postDate($grapSales) . "' AND (TIME(created_at) BETWEEN '" . $runTimeStart . ":00:00' AND '" . $runTimeEnd . ":59:59' ) AND status_order = '000'")->first()->total_sales;
                $grapTotalPriceDiscount[] = Orders::selectRaw("SUM(order_discount) as total_discount")->whereRaw("DATE(created_at) = '" . postDate($grapSales) . "' AND (TIME(created_at) BETWEEN '" . $runTimeStart . ":00:00' AND '" . $runTimeEnd . ":59:59' ) AND status_order = '000'")->first()->total_discount;
                $j += 2;
            }
           }else{
            $order_detail = $order_detail->whereRaw("tb_order.created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
            $discount_details = $discount_details->whereRaw("tb_order.created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
            for ($i = 1; $i <= 12; $i++) {
                if($i <= 7){
                $grapTotalPrice[] = Orders::selectRaw("SUM(order_total - order_discount) as total_sales")->whereRaw("DATE(created_at) = (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) +".($i-1)." DAY))  AND status_order = '000'")->first()->total_sales;
                $grapTotalPriceDiscount[] = Orders::selectRaw("SUM(order_discount) as total_discount")->whereRaw("DATE(created_at) = (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) +" . ($i - 1) . " DAY))  AND status_order = '000'")->first()->total_discount;
                }
            }
            }
        $order_detail =  $order_detail->first();
        $discount_details =  $discount_details->get();
        $sum_discount = 0;
        foreach ($discount_details as $discount_detail) {
            $sum_discount += $discount_detail->order_discount;
        }
        // pie(แผนภูมิวงกลม)
        $pieStatus = @$request->pieStatus;
        $pieStatusSuccess = Orders::query()->where('status_order','000');
        $pieStatusPending = Orders::query()->where('status_order','001');
        $pieStatusExpired = Orders::query()->where('status_order','999');
        $pieStatusCancel  = Orders::query()->where('status_order', '002');
        if($pieStatus == 'D'){
            $pieStatusSuccess = $pieStatusSuccess->whereRaw("DATE(created_at) = '". date('Y-m-d')."'");
            $pieStatusPending = $pieStatusPending->whereRaw("DATE(created_at) = '" . date('Y-m-d') . "'");
            $pieStatusExpired = $pieStatusExpired->whereRaw("DATE(created_at) = '" . date('Y-m-d') . "'");
            $pieStatusCancel = $pieStatusCancel->whereRaw("DATE(created_at) = '" . date('Y-m-d') . "'");
        }else if($pieStatus == 'W'){
            $pieStatusSuccess = $pieStatusSuccess->whereRaw("created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
            $pieStatusPending = $pieStatusPending->whereRaw("created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
            $pieStatusExpired = $pieStatusExpired->whereRaw("created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
            $pieStatusCancel = $pieStatusCancel->whereRaw("created_at BETWEEN  (DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)) AND (DATE(NOW() + INTERVAL (6 - WEEKDAY(NOW())) DAY))  ");
        
        }else if($pieStatus == 'M'){
            $pieStatusSuccess = $pieStatusSuccess->whereRaw("created_at BETWEEN  (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH))  ");
            $pieStatusPending = $pieStatusPending->whereRaw("created_at BETWEEN  (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH))  ");
            $pieStatusExpired = $pieStatusExpired->whereRaw("created_at BETWEEN  (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH))  ");
            $pieStatusCancel = $pieStatusCancel->whereRaw("created_at BETWEEN  (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) AND ( LAST_DAY(NOW()+ INTERVAL 0 MONTH))  ");
        }else if($pieStatus == 'Y'){
            $pieStatusSuccess = $pieStatusSuccess->whereRaw("YEAR(created_at) = '" . $thisYear . "' ");
            $pieStatusPending = $pieStatusPending->whereRaw("YEAR(created_at) = '" . $thisYear . "' ");
            $pieStatusExpired = $pieStatusExpired->whereRaw("YEAR(created_at) = '" . $thisYear . "' ");
            $pieStatusCancel = $pieStatusCancel->whereRaw("YEAR(created_at) = '" . $thisYear . "' ");
        }
        $pieStatusSuccess = $pieStatusSuccess->count();
        $pieStatusPending = $pieStatusPending->count();
        $pieStatusExpired = $pieStatusExpired->count();
        $pieStatusCancel = $pieStatusCancel->count();
        
        $pieStatusArray = [
            'pieStatusSuccess'=> $pieStatusSuccess,
            'pieStatusPending'=> $pieStatusPending,
            'pieStatusExpired'=> $pieStatusExpired,
            'pieStatusCancel'=> $pieStatusCancel,
            'pieStatusAll'=> $pieStatusSuccess + $pieStatusCancel + $pieStatusPending + $pieStatusExpired,
        ];

        $paymentChanel = @$request->channel;
        // dd($voucher_charts);
        $data = array(
            'voucher' => $voucher,
            'blog' => $blog,
            'order' => $order,
            'member' => $member,
            'order_detail' => $order_detail,
            'orders' => $orders,
            'order_group' => $order_group,
            'voucher_charts' => $voucher_charts,
            'grapTotalPrice' => $grapTotalPrice,
            'grapTotalPriceDiscount' => $grapTotalPriceDiscount,
            // 'month2' => $month2,
            // 'month3' => $month3,
            // 'month4' => $month4,
            // 'month5' => $month5,
            // 'month6' => $month6,
            // 'month7' => $month7,
            // 'month8' => $month8,
            // 'month9' => $month9,
            // 'month10' => $month10,
            // 'month11' => $month11,
            // 'month12' => $month12,
            // 'month_discount1' => $month_discount1,
            // 'month_discount2' => $month_discount2,
            // 'month_discount3' => $month_discount3,
            // 'month_discount4' => $month_discount4,
            // 'month_discount5' => $month_discount5,
            // 'month_discount6' => $month_discount6,
            // 'month_discount7' => $month_discount7,
            // 'month_discount8' => $month_discount8,
            // 'month_discount9' => $month_discount9,
            // 'month_discount10' => $month_discount10,
            // 'month_discount11' => $month_discount11,
            // 'month_discount12' => $month_discount12,
            'sum_discount' => $sum_discount,
            'filterTopSales5' => $getFilterSaleVoucher,
            'grapSales' => $grapSales,
            'pieStatus' => $pieStatus,
            'pieStatusArray' => $pieStatusArray,
            'paymentChanel' => $paymentChanel,
        );
        // dd($data);
        return view('backoffice.dashboard.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
