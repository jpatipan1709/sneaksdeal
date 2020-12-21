<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use DB;
use App\Http\Controllers\PHPExcel_Style_Alignment;
use Carbon\Carbon;
use Datatables;
use Session;
class ShowpdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('tb_order')
        ->select('tb_order.*','tb_order.id as o_id','tb_member.*','tb_order.created_at as m_create','tb_discount.*')
        ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
        ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
        ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
        ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
        ->whereNull('tb_order.deleted_at')
        ->get();
        
        return view('backoffice.pdf.index');
    }

    public function index2()
    {
        $data = DB::table('tb_order')
        ->select('tb_order.*','tb_order.id as o_id','tb_member.*','tb_order.created_at as m_create','tb_discount.*')
        ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
        ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
        ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
        ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
        ->whereNull('tb_order.deleted_at')
        ->get();
        
        // $data2 = DB::table('tb_voucher')
        // ->leftjoin('tb_blog','tb_voucher.relation_blogid','=','tb_blog.id_blog')
        // ->leftjoin('system_admin','tb_blog.id_blog','=','system_admin.blog_id_at')
        // ->where('id_admin','=',Session::get('id_admin'))
        // ->get();
        // dd($data2);
        return view('backoffice.pdf.index2');
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
        $tb_order =  DB::table('tb_order')
                        ->select('tb_order.*','tb_member.*','tb_order_detail.*','tb_order.created_at as m_create','tb_discount.*','provinces.name_th as p_name','amphures.name_th as a_name','districts.name_th as d_name','tb_order.id as o_id')
                        ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                        ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                        ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                        ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
                        ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
                        ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
                        ->where('tb_order.id', '=', $id)
                        ->whereNull('tb_order.deleted_at')
                        ->first();
                        
        $sum_orders = DB::table('tb_order')
                        ->select('tb_order_detail.*','tb_discount.*','tb_order.order_discount')
                        ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                        ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                        ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                        ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                        ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                        // ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
                        ->where('id_main', '=', Session::get('main_id_at'))
                        ->where('tb_order.id', '=', $id)
                        ->whereNull('tb_order.deleted_at')
                        // ->groupBy('tb_order.id')
                        ->get();
        // dd($sum_orders);
     
        $data = array(
            'tb_order' => $tb_order,
        );
        return view('backoffice.pdf.show', $data);
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

    public function queryDatatable(Request $request)
    {
        // $data = App\Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
        //                 ->leftjoin('tb_order_detail','order_vouchers.order_detail_id','=','tb_order_detail.odt_id')
        //                 ->leftjoin('main_voucher','tb_order_detail.main_id','=','main_voucher.id_main')
        //                 ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
        //                 ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
        //                 ->where('id_admin','=',Session::get('id_admin'))
        //                 ->where('main_id','=',$rowVoucher->relation_mainid)
        //                 ->where(function ($query) {
        //                     $query->where('status_order', '=', '000')
        //                         ->orWhere('status_order', '=', '001');
        //                 })->get();

        $data = DB::table('tb_order')
                        ->select('tb_order.*','tb_order.id as o_id','tb_member.*','tb_order.created_at as m_create','tb_discount.*')
                        ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                        ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                        ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                        ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')
                        ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                        ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                        ->where('id_admin','=',Session::get('id_admin'))
                        ->where('status_order','=',000)
                        ->whereNull('tb_order.deleted_at')
                        ->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('user_id', function ($data) {
                return $data->name_member.' '.$data->lastname_member;
                
            })
            ->addColumn('status_order', function ($data) {
                if(($data->status_order) == 000){
                    return "<span class='text-success'>สำเร็จ</span>";
                }else{
                    return "<span class='text-danger'>กำลังดำเนินการ</span>";
                }
            })
            ->addColumn('status_payment', function ($data) {
            
                 $status_payment2 = substr($data->status_payment,0,3);
                if ( $status_payment2 == "001") {
                    $status_payment = 'บัตรเครดิตวีซ่า / มาสเตอร์การ์ด';
                } else if ( $status_payment2 == "002") {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์ธนาคาร';
                }else{
                    $status_payment = 'ชําระเงินผ่านตู้ atm';
                }
                return $status_payment;    

            })
            ->addColumn('discount_id', function ($data) {

                if($data->discount_id != ""){
                    if($data->discount_main != 0){
                        return $data->discount_code;
                    }else{
                        return "-";
                    }
                    
                }else{
                    return "-";
                }

            })
            ->addColumn('order_total', function ($data) {
            
                return $data->order_total;

            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, true, false, false, 'backoffice/reportBlog');
                return $Manage;
            })
            // ->addColumn('Print', function ($data) {
            //     $Manage = buttonReport('ShowPDF', true, $data->id);
            //     return $Manage;
            //     // $Manage = buttonReport($data->id, true, 'backoffice/order');
            //     // return $Manage;
            // })
            ->rawColumns(['No','status_order','user_id', 'Manage'])
            ->make(true);
    }

    public function queryDatatable2(Request $request)
    {
       
        $data = DB::table('tb_voucher')
                    ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                    ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                    ->where('system_admin.id_admin','=',Session::get('id_admin'))
                    ->get();
        
        return Datatables::of($data)->addIndexColumn()
        ->addColumn('No', "")
        ->addColumn('name_voucher', function ($data) {
            return $data->name_voucher;
        })
        ->addColumn('date_open', function ($data) {
            return $data->date_open;
        })
        ->addColumn('date_close', function ($data) {
            return $data->date_close;
        })
        ->addColumn('price_agent', function ($data) {
            return $data->price_agent;
        })
        ->addColumn('price_sale', function ($data) {
            return $data->price_sale;
        })

        ->addColumn('name_blog', function ($data) {
            return $data->name_main;
        })
        
        ->addColumn('Manage', function ($data) {
        $Manage = buttonManageData($data->voucher_id, true, false, false, 'backoffice/reportBlog');
        return $Manage;
        })
        // ->addColumn('Print', function ($data) {
        //     $Manage = buttonReport('ShowPDF', true, $data->id);
        //     return $Manage;
        //     // $Manage = buttonReport($data->id, true, 'backoffice/order');
        //     // return $Manage;
        // })
        ->rawColumns(['No','name_voucher','name_blog', 'Manage'])
        ->make(true);
    }
    public function ShowPDF(Request $request){
        
        $time = explode('-',$request->time_open);
        
        $timestart = str_replace('/','-',$time[0]);
        $timeend = str_replace('/','-',$time[1]);

        $sum_order = DB::table('system_admin')
                    ->select('tb_order.*','tb_order_detail.*',DB::raw("SUM(tb_order_detail.total) as sum_total"),'tb_voucher.*','main_voucher.*','system_admin.*')
                    ->leftjoin('main_voucher','system_admin.main_id_at','=','main_voucher.id_main')
                    ->leftjoin('tb_voucher','main_voucher.id_main','=','tb_voucher.relation_mainid')
                    ->leftjoin('tb_order_detail','tb_voucher.voucher_id','=','tb_order_detail.voucher_id')
                    ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
                    ->where('system_admin.id_admin','=',Session::get('id_admin'))
                    ->where('tb_order.status_order','=',000)
                    ->whereBetween('tb_order.created_at', [$timestart, $timeend])
                    ->whereNull('tb_order.deleted_at')
                    ->first();
      
        $orders = DB::table('system_admin')
                    ->select('tb_order.*','tb_order_detail.*','tb_voucher.*','tb_discount.*','tb_member.*','tb_member.email as member_email','main_voucher.*','system_admin.*','tb_order.created_at as o_create')
                    ->leftjoin('main_voucher','system_admin.main_id_at','=','main_voucher.id_main')
                    ->leftjoin('tb_voucher','main_voucher.id_main','=','tb_voucher.relation_mainid')
                    ->leftjoin('tb_order_detail','tb_voucher.voucher_id','=','tb_order_detail.voucher_id')
                    ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
                    ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                    ->leftjoin('tb_discount','tb_order_detail.discount','=','tb_discount.discount_id')
                    ->where('system_admin.id_admin','=',Session::get('id_admin'))
                    ->where('tb_order.status_order','=',000)
                    ->whereBetween('tb_order.created_at', [$timestart, $timeend])
                    ->whereNull('tb_order.deleted_at')
                    ->get();
      
            $sum_discount = 0 ;
            foreach( $orders as  $order){
                if($order->discount_main != 0 ){
                    $sum_discount += $order->discount_bath;
                }
            }
        

        // dd($sum_discount);

        Excel::create(Carbon::now()->format('YmdHis').'_Process_data', function($excel) use($orders,$sum_order,$sum_discount) {
           
            
            $excel->setTitle('Report on Process');
            $excel->setCreator('Me')->setCompany('Our Code World');
            $excel->setDescription('A demonstration to change the file properties');

                function DateThai($strDate)
                {
                    $strYear = date("Y",strtotime($strDate))+543;
                    $strMonth= date("n",strtotime($strDate));
                    $strDay= date("j",strtotime($strDate));
                    $strHour= date("H",strtotime($strDate));
                    $strMinute= date("i",strtotime($strDate));
                    $strSeconds= date("s",strtotime($strDate));
                    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
                    $strMonthThai=$strMonthCut[$strMonth];
                    return "$strDay $strMonthThai $strYear";
                }

                $excel->sheet('Sheet 1', function ($sheet) use ($orders,$sum_order,$sum_discount) {
                $sheet->setOrientation('landscape');
                $sheet->mergeCells('A1:P1');
                $sheet->getStyle('A1:G1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->setWidth('A',10);
                $sheet->setWidth('B',30);
                $sheet->setAutoSize(true);
                $sheet->setWidth('D',15);
                $sheet->setWidth('E',10);
                $sheet->setAutoSize(true);
                $sheet->setWidth('G',20);
                $sheet->setCellValueByColumnAndRow(0, 1, "รายงานยอดขาย ณ วันที่ ".DateThai($sum_order->created_at));
                $sheet->cell('A1:V2', function($cell) use($orders,$sum_order) {
                    $cell->setFont(array(
                        'bold' =>  true,
                        'size' => 10
                    ));
                });
                $sheet->row(2, array(
                    '#',
                    'รหัสออเดอร์',
                    'รหัส Voucher',
                    'ชื่อ ผู้ซื้อ',
                    'ชื่อ โรงแรม',
                    'ชื่อ Voucher',
                    'วันที่ซื้อ',
                    'วันที่ใช้งาน',
                    'สถานะการใช้งาน',
                    'สถานะการชำระให้โรงแรม',
                    'ราคา/ชิ้น',
                    'จำนวน',
                    'ราคารวม',
                    'ส่วนลด',
                    'รหัสส่วนลด',
                    'เงินที่รับสุทธิ',
                    'เบอร์โทร',
                    'อีเมล์',
                ));
               
                $sheet->getStyle('A2:H2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            
                foreach ($orders as $key => $value) {
                    $order_voucher = DB::table('order_vouchers')->where('orders_id','=',$value->id)->where('voucher_id','=',$value->voucher_id)->first();
                    if($value->discount_main != 0){
                        $discount_rate = $value->discount_bath;
                        $discount_code = $value->discount_code;
                    }else{
                        $discount_rate = 0;
                        $discount_code = "";
                    }

                    if($order_voucher->stat_voucher == 'y'){
                        $stat_voucher = "ใช้งานแล้ว";
                    }else{
                        $stat_voucher = "ยังไม่ได้ใช้งาน";
                    }
                    if($order_voucher->pay_status == '1'){
                        $pay_status = "ชำระแล้ว";
                    }else{
                        $pay_status = "ยังไม่ได้ชำระ";
                    }
                    $sheet->row($key+3, array(
                        $key+1,
                        $value->order_id,
                        $order_voucher->code_voucher,
                        $value->name_member.' '.$value->lastname_member,
                        $value->name_main,
                        $value->name_voucher,
                        $value->o_create,
                        $order_voucher->use_date,
                        $stat_voucher,
                        $pay_status,
                        $value->priceper,
                        $value->qty,
                        $value->total,
                        $discount_rate,
                        $discount_code,
                        $value->total - $discount_rate,
                        $value->tel_member,
                        $value->member_email,
                       
                    ));
                }
                $sheet->setCellValueByColumnAndRow(19, 2, "รวมทั้งหมด");
                $sheet->setCellValueByColumnAndRow(20, 2, "ส่วนลด");
                $sheet->setCellValueByColumnAndRow(21, 2, "รวมสุทธิ");

                $count_order = (int)count($orders);
                $start_row = $count_order+3;
                $end_row = $count_order+3;
                $area = 'A'.$start_row.':G'.$end_row;
                $sheet->getStyle($area)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                $sheet->cell($area, function($cell) use($orders,$sum_order) {
                    $cell->setFont(array(
                        'bold' =>  true,
                        'size' => 10
                    ));
                });
                $sheet->setCellValueByColumnAndRow(19, 3, $sum_order->sum_total);
                // $sheet->setCellValueByColumnAndRow(7, $end_row, $sum_order->sum_total);

                $count_order = (int)count($orders);
                $start_row = $count_order+4;
                $end_row = $count_order+4;
                $area = 'A'.$start_row.':G'.$end_row;
                $sheet->getStyle($area)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                $sheet->cell($area, function($cell) use($orders,$sum_order) {
                    $cell->setFont(array(
                        'bold' =>  true,
                        'size' => 10
                    ));
                });
                $sheet->setCellValueByColumnAndRow(20, 3, $sum_discount);
                // $sheet->setCellValueByColumnAndRow(7, $end_row, $sum_discount);

                $count_order = (int)count($orders);
                $start_row = $count_order+5;
                $end_row = $count_order+5;
                $area = 'A'.$start_row.':G'.$end_row;
                $sheet->getStyle($area)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                $sheet->cell($area, function($cell) use($orders,$sum_order) {
                    $cell->setFont(array(
                        'bold' =>  true,
                        'size' => 10
                    ));
                });
                $sheet->setCellValueByColumnAndRow(21, 3, $sum_order->sum_total - $sum_discount);
                
            });

        })->download('xlsx');
    }


    public function ShowPDF2(){
        $orders = DB::table('tb_voucher')
                    ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
                    ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
                    ->where('system_admin.id_admin','=',Session::get('id_admin'))
                    ->get();

        Excel::create(Carbon::now()->format('YmdHis').'_Process_data', function($excel) use($orders) {
           
            
            $excel->setTitle('Report on Process');
            $excel->setCreator('Me')->setCompany('Our Code World');
            $excel->setDescription('A demonstration to change the file properties');


                $excel->sheet('Sheet 1', function ($sheet) use ($orders) {
               
                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1:G1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->setCellValueByColumnAndRow(0, 1, "รายงานยอด Voucher");
                $sheet->cell('A1:G2', function($cell) use($orders) {
                    $cell->setFont(array(
                        'bold' =>  true,
                        'size' => 10
                    ));
                });
                $sheet->row(2, array(
                    '#',
                    'ชื่อ Voucher',
                    'วันที่เริม',
                    'วันที่สิ้นสุด',
                    'จำนวน',
                    'ราคาเต็ม',
                    'ราคาขาย',
                    
                ));
               
                $sheet->getStyle('A2:G2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
               
                foreach ($orders as $key => $value) {
                    $sheet->row($key+3, array(
                        $key+1,
                        $value->name_voucher,
                        $value->date_open,
                        $value->date_close,
                        $value->qty_voucher,
                        $value->price_agent,
                        $value->price_sale,
                        
                       
                    ));
                }
            });

        })->download('xlsx');
    }

    public function show2($id){
        $data = DB::table('tb_voucher')
        ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
        ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
        ->where('tb_voucher.voucher_id','=',$id)
        ->first();

        $data2 = array(
            'data' => $data
        );

    
        return view('backoffice.pdf.show2', $data2);
    }
}
