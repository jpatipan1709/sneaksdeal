<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $system_admin = DB::table('main_voucher')
                        ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                        ->where('main_id_at','=',Session::get('main_id_at'))
                        ->first();
        // $order_sum = DB::table('tb_order')
        //         ->select(DB::raw('SUM(tb_order.order_total) as total_sales'))
        //         ->leftjoin('tb_order_detail','tb_order.id','=','tb_order_detail.order_id')
        //         ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')
        //         ->leftjoin('tb_blog','tb_voucher.relation_blogid','=','tb_blog.id_blog')
        //         ->leftjoin('system_admin','tb_blog.id_blog','=','system_admin.blog_id_at')
        //         ->where('system_admin.id_admin','=',Session::get('id_admin'))
        //         ->where('tb_order.status_order','=',1)
        //         ->get();
        
        $order_detail = DB::table('tb_order_detail')
                            ->select('tb_order_detail.*','tb_voucher.*','main_voucher.*','tb_order.*',DB::raw('SUM(tb_order_detail.total) as total_sales'),DB::raw('SUM(tb_order.order_discount) as total_discount'))
                            ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
                            ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')   
                            ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                            // ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                            ->where('id_main','=',Session::get('main_id_at'))
                            ->where('status_order','=','000')
                            
                            ->first();

        $order_details = DB::table('tb_order')
                ->leftjoin('tb_order_detail','tb_order.id','=','tb_order_detail.order_id')
                ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')
                ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                // ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                ->where('id_main','=',Session::get('main_id_at'))
                ->where('status_order','=','000')
                ->get();

        $discount_details = DB::table('tb_order')
                            ->select('tb_order_detail.*','tb_order.*','tb_discount.*',DB::raw('SUM(tb_order.order_discount) as total_discount'))
                            ->leftjoin('tb_order_detail','tb_order.id','=','tb_order_detail.order_id')
                            ->leftjoin('tb_discount','tb_order_detail.discount','=','tb_discount.discount_id')
                            // ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')   
                            // ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                            // ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                            ->where('main_id','=',Session::get('main_id_at'))
                            // ->where('status_order','=','000')
                            ->groupBy('id')
                            ->get();
        // dd( $discount_details);
        $sum_discount = 0;
        foreach($discount_details as $discount_detail){
            if( $discount_detail->discount_main != 0){
                $sum_discount += $discount_detail->order_discount;
            }
           
        }
      
        $data = array(
        'system_admin' => $system_admin,
        // 'order_sum' => $order_sum,
        'order_detail' => $order_detail,
        'order_details' => $order_details,
        'sum_discount' => $sum_discount
        );

     
        return view('backoffice.sale.index',$data);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function blogdetail(){

        // dd(Session::get('id_admin'));
        $order_detail = DB::table('main_voucher')
                            ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                            ->where('main_id_at','=',Session::get('main_id_at'))
                            ->first();

        // dd($order_detail);
        $data = array(
            'order_detail' => $order_detail

        );

        return view('backoffice.sale.showblog',$data);
    }
}
