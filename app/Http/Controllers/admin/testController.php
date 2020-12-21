<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use DB;
use App\Orders;
use Excel;
use App\Exports\OrdersExport;
class ShowPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
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
        echo $id;
        // $footballer = Footballerdetail::find($id);
        // $pdf = PDF::loadView('pdf', compact('footballer'));
        // return $pdf->download('footballerdetail.pdf');
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

    public function export(Request $request)
    {
        $cust_have = DB::table('customer')->leftjoin('orderrents','orderrents.order_customer', '=' ,'customer.id')
            ->where('order_active',1)
            ->groupby('customer.id')
            ->selectRaw('customer.*')
            ->orderby('id','asc')
            ->get();
        $cust_all = DB::table('customer')->orderby('id','asc')->get();
        if (count($cust_all) != 0) {
            if (count($cust_have) != 0) {
                Excel::create(Carbon::now()->format('YmdHis').'_Process_data', function($excel) use($request,$cust_have,$cust_all) {
                    $excel->setTitle('Report on Process');
                    $section = $request->type;
                    foreach ($cust_all as $key_2 => $plus_2) {
                        $plus_2->no = $key_2+1;
                        $plus_2->status_check = '0';
                    }
                    $cust_show_nothave = array();
                    foreach ($cust_all as $key1 => $value1) {
                        foreach ($cust_have as $key2 => $value2) {
                            if ($value1->id == $value2->id) {
                                $value1->status_check = '1';
                            }
                        }
                    }
                    foreach ($cust_all as $key3 => $value3) {
                        if ($value3->status_check == '0') {
                            $cust_show_nothave[] = $value3;
                        }
                    }
                    if ($section == 1) {
                        foreach ($cust_have as $key_1 => $plus_1) {
                            $plus_1->no = $key_1+1;
                        }
                        $sQuery = $cust_have;
                    }elseif ($section == 2) {
                        foreach ($cust_show_nothave as $key_3 => $plus_3) {
                            $plus_3->no = $key_3+1;
                        }
                        $sQuery = $cust_show_nothave;
                    }else{
                        $sQuery = $cust_all;
                    }
                    foreach ($sQuery as $key => $value) {
                        if ($value->cust_active == '1') {
                            $value->cust_active = 'เปิดใช้';
                        }else{
                            $value->cust_active = 'ปิดใช้งาน';
                        }
                    }
                    $excel->sheet('Customer', function($sheet) use($sQuery,$section){
                        ///////////////////// style ///////////////////////
                            $style = array(
                                'alignment' => array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                )
                            );
                            $sheet->getDefaultStyle()->applyFromArray($style);
                            $sheet->mergeCells('A1:F1');
                            $sheet->setSize('A1', 10, 80);
                            $sheet->setSize('B1', 25, 20);
                            $sheet->setSize('C1', 50, 20);
                            $sheet->setSize('D1', 20, 20);
                            $sheet->setSize('E1', 25, 20);
                            $sheet->setSize('F1', 20, 20);
                            $sheet->setBorder('A1:F1', 'thin');
                            $sheet->setBorder('A2:F2', 'thin');
                        ///////////////////// style ///////////////////////
                        ///////////////////// Data Head ///////////////////////
                            if ($section == '1') {
                                $status = 'ลูกค้าที่มีรายการเช่า';
                            }elseif($section == '2'){
                                $status = 'ลูกค้าที่ไม่มีรายการเช่า';
                            }else{
                                $status = 'ทั้งหมด';
                            }
                            $sheet->cell('A1', function($cell) use($status) {
                                $cell->setValue('รายงานลูกค้า'.$status);
                                $cell->setFont(array(
                                    'bold' =>  true,
                                    'size' => 16
                                ));
                            });
                            $sheet->getStyle('A1')->getAlignment()->applyFromArray(
                                array(
                                    'horizontal' => 'center')
                            );
                            $sheet->row(2, array(
                                '#',
                                'ชื่อ-นามสกุล (ชื่อเล่น)',
                                'ที่อยู่',
                                'เบอร์โทร',
                                'อีเมล์',
                                'สถานะ',
                            ));
                        ///////////////////// Data Head ///////////////////////
                        //////////////////// Data Content //////////////////////
                        foreach ($sQuery as $key => $value) {
                            $sheet->row($key+3, array(
                                $key+1,
                                $value->cust_name,
                                $value->cust_address,
                                $value->cust_phone,
                                $value->cust_email,
                                $value->cust_active,
                            ));
                        }
                        //////////////////// Data Content //////////////////////
                    });
                })->download('xlsx');
            }else{
                $error = '1' ;
                $data = array(
                    'error' => $error
                );
                return view('backend.customer.customer_list',$data);
            }
        }else{
            $error = '1' ;
            $data = array(
                'error' => $error
            );
            return view('backend.customer.customer_list',$data);
        }
        exit;
    }
    
}
