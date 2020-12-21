<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orders;
use App\Order_voucher;
use App\Order_details;
use App\LogAddressOrder;
use DB;
use App\Model\admin\BlogModel;
use Datatables;
use Storage;
use App\Model\admin\SystemFileModel;
use App\Model\admin\TypeBlogModel;
use Session;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use Excel;
use App\Http\Controllers\PHPExcel_Style_Alignment;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function OrderPDF(Request $request)
    {
        $hotel = @$request->hotel;
        $date = @$request->date;
        $status = @$request->status;

        if ($date != '') {
            $d2 = str_replace(' / ', ' - ', str_replace('-', '/', $date));
            $time = explode(' - ', $d2);
            $time_start = date('Y-m-d H:i:s', strtotime($time[0]));
            $time_end = date('Y-m-d H:i:s', strtotime($time[1]));
        } else {
            $time_start = '';
            $time_end = '';
        }
        $main = $hotel;
        if ($hotel == 0 && $date == "") {
            $sum_order = Orders::query()
                ->select('tb_order.*', 'tb_order_detail.*', DB::raw("SUM(tb_order_detail.total) as sum_total"), 'tb_voucher.*', 'main_voucher.*')
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main');
            if ($status == 'no') {
                $sum_order = $sum_order->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $sum_order = $sum_order->where('tb_order.status_order', $status);
            }
            $sum_order = $sum_order->first();

            $orders = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as o_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'tb_member.districts_id AS d_name',
                    'tb_member.amphures_id AS a_name',
                    'tb_member.address_member',
                    'main_voucher.name_main',
                    'provinces.name_th as p_name',
                    'tb_voucher.name_voucher',
                    'tb_order_detail.qty',
                    'tb_order_detail.total',
                    'tb_member.tel_member',
                    'tb_member.email'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftJoin('provinces', 'tb_member.provinces_id', 'provinces.id');
            if ($status == 'no') {
                $orders = $orders->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $orders = $orders->where('tb_order.status_order', $status);
            }
            $orders = $orders->orderBy('tb_order.id', 'desc')->get();

            $sum_discount = 0;
            foreach ($orders as $order) {
                $sum_discount += ($order->discount_bath == '' ? 0 : $order->discount_bath);
            }
        } elseif ($hotel != 0 && $date == "") {
            $sum_order = Orders::query()
                ->select('tb_order.*', 'tb_order_detail.*', DB::raw("SUM(tb_order_detail.total) as sum_total"), 'tb_voucher.*', 'main_voucher.*')
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main');
            if ($status == 'no') {
                $sum_order = $sum_order->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $sum_order = $sum_order->where('tb_order.status_order', $status);
            }
            $sum_order = $sum_order->where('main_voucher.id_main', '=', $hotel)
                ->first();

            $orders = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as o_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'tb_member.districts_id AS d_name',
                    'tb_member.amphures_id AS a_name',
                    'tb_member.address_member',
                    'main_voucher.name_main',
                    'provinces.name_th as p_name',
                    'tb_voucher.name_voucher',
                    'tb_order_detail.qty',
                    'tb_order_detail.total',
                    'tb_member.tel_member',
                    'tb_member.email'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftJoin('provinces', 'tb_member.provinces_id', 'provinces.id');
            if ($status == 'no') {
                $orders = $orders->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $orders = $orders->where('tb_order.status_order', $status);
            }
            $orders = $orders->where('main_voucher.id_main', '=', $hotel)
                ->orderBy('tb_order.id', 'desc')
                ->get();

            $sum_discount = 0;
            foreach ($orders as $order) {
                $sum_discount += $order->discount_bath;
            }
        } elseif ($hotel == 0 && $date != "") {
            $sum_order = Orders::query()
                ->select('tb_order.*', 'tb_order_detail.*', DB::raw("SUM(tb_order_detail.total) as sum_total"), 'tb_voucher.*', 'main_voucher.*')
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main');
            if ($status == 'no') {
                $sum_order = $sum_order->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $sum_order = $sum_order->where('tb_order.status_order', $status);
            }
            $sum_order = $sum_order->whereBetween('tb_order.created_at', [$time_start, $time_end])
                ->first();


            $orders = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as o_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'tb_member.districts_id AS d_name',
                    'tb_member.amphures_id AS a_name',
                    'tb_member.address_member',
                    'main_voucher.name_main',
                    'provinces.name_th as p_name',
                    'tb_voucher.name_voucher',
                    'tb_order_detail.qty',
                    'tb_order_detail.total',
                    'tb_member.tel_member',
                    'tb_member.email'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftJoin('provinces', 'tb_member.provinces_id', 'provinces.id');
            if ($status == 'no') {
                $orders = $orders->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $orders = $orders->where('tb_order.status_order', $status);
            }
            $orders = $orders->whereBetween('tb_order.created_at', [$time_start, $time_end])
                ->orderBy('tb_order.id', 'desc')
                ->get();


            $sum_discount = 0;
            foreach ($orders as $order) {
                $sum_discount += $order->discount_bath;
            }
        } else {
            $sum_order = Orders::query()
                ->select('tb_order.*', 'tb_order_detail.*', DB::raw("SUM(tb_order_detail.total) as sum_total"), 'tb_voucher.*', 'main_voucher.*')
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main');
            if ($status == 'no') {
                $sum_order = $sum_order->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $sum_order = $sum_order->where('tb_order.status_order', $status);
            }
            $sum_order = $sum_order->where('main_voucher.id_main', '=', $hotel)
                ->whereBetween('tb_order.created_at', [$time_start, $time_end])
                ->first();

            $orders = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as o_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'tb_member.districts_id AS d_name',
                    'tb_member.amphures_id AS a_name',
                    'tb_member.address_member',
                    'main_voucher.name_main',
                    'provinces.name_th as p_name',
                    'tb_voucher.name_voucher',
                    'tb_order_detail.qty',
                    'tb_order_detail.total',
                    'tb_member.tel_member',
                    'tb_member.email'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftJoin('provinces', 'tb_member.provinces_id', 'provinces.id');
            if ($status == 'no') {
                $orders = $orders->whereNotIn('tb_order.status_order', ['001', '002', '000']);
            } else if ($status == 'all') {
            } else {
                $orders = $orders->where('tb_order.status_order', $status);
            }
            $orders = $orders->where('main_voucher.id_main', '=', $hotel)
                ->whereBetween('tb_order.created_at', [$time_start, $time_end])
                ->orderBy('tb_order.id', 'desc')
                ->get();

            $sum_discount = 0;
            foreach ($orders as $order) {
                $sum_discount += $order->discount_bath;
            }
        }

        Excel::create(Carbon::now()->format('YmdHis') . '_Process_data', function ($excel) use ($orders, $sum_order, $sum_discount, $status) {


            $excel->setTitle('Report on Process');
            $excel->setCreator('Me')->setCompany('Our Code World');
            $excel->setDescription('A demonstration to change the file properties');

            function DateThai($strDate)
            {
                $strYear = date("Y", strtotime($strDate)) + 543;
                $strMonth = date("n", strtotime($strDate));
                $strDay = date("j", strtotime($strDate));
                $strHour = date("H", strtotime($strDate));
                $strMinute = date("i", strtotime($strDate));
                $strSeconds = date("s", strtotime($strDate));
                $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
                $strMonthThai = $strMonthCut[$strMonth];
                return "$strDay $strMonthThai $strYear";
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($orders, $sum_order, $sum_discount, $status) {
                $sheet->setOrientation('landscape');
                $sheet->mergeCells('A1:P1');
                $sheet->getStyle('A1:G1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                // $sheet->setWidth('A',10);
                // $sheet->setWidth('B',30);
                // $sheet->setAutoSize(true);
                // $sheet->setWidth('D',15);
                // $sheet->setWidth('E',10);
                // $sheet->setAutoSize(true);
                // $sheet->setWidth('G',20);
                $sheet->setCellValueByColumnAndRow(0, 1, "รายงานยอดขาย ณ วันที่ " . DateThai(date("Y-m-d")));
                $sheet->cell('A1:V2', function ($cell) use ($orders, $sum_order) {
                    $cell->setFont(array(
                        'bold' => true,
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
                    'ที่อยู่',
                    'สถานะรายการสินค้า',
                ));

                $sheet->getStyle('A2:H2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $address = "";
                foreach ($orders as $key => $value) {
                    //                    dd($value);
                    //                    $order_voucher = DB::table('order_vouchers')->where('orders_id', '=', $value->id)->where('voucher_id', '=', $value->voucher_id)->first();
                    if ($value->stat_voucher == 'y') {
                        $stat_voucher = "ใช้งานแล้ว";
                    } else {
                        $stat_voucher = "ยังไม่ได้ใช้งาน";
                    }
                    if ($value->pay_status == '1') {
                        $pay_status = "ชำระเงินแล้ว";
                    } else {
                        $pay_status = "ยังไม่ได้ชำระ";
                    }
                    if ($value->provinces_id == 1) {
                        if ($value->districts_id != "") {
                            $d = 'แขวง';
                        } else {
                            $d = '';
                        }

                        if ($value->amphures_id != "") {
                            $a = 'เขต';
                        } else {
                            $a = '';
                        }
                    } else {
                        if ($value->districts_id != "") {
                            $d = 'ตำบล';
                        } else {
                            $d = '';
                        }

                        if ($value->amphures_id != "") {
                            $a = 'อำเภอ';
                        } else {
                            $a = '';
                        }
                    }


                    $address = $value->address_member . " " . $d . $value->districts_id . " " . $a . $value->amphures_id . " จ." . $value->p_name . " " . $value->zip_code;
                    $status_order = $value->status_order;
                    if ($status_order == "000") {
                        $status_order2 = "<span class='text-success'>ชำระเงินแล้ว</span>";
                    } else if ($status_order == "001") {
                        $status_order2 = "<span class='text-warning'>กำลังดำเนินการ</span>";
                    } else if ($status_order == "002") {
                        $status_order2 = "<span class='text-warning'>ชำระเงินล้มเหลว</span>";
                    } else {
                        $status_order2 = "<span class='text-danger'>ยกเลิกการชำระเงิน</span>";
                    }

                    $sheet->row($key + 3, array(
                        $key + 1,
                        str_pad($value->id, 11, "0", STR_PAD_LEFT),
                        $value->code_voucher,
                        $value->name_member . ' ' . $value->lastname_member,
                        $value->name_main,
                        $value->name_voucher,
                        $value->o_create,
                        $value->use_date,
                        $stat_voucher,
                        $pay_status,
                        $value->priceper,
                        $value->qty,
                        $value->total,
                        $value->order_discount,
                        ($value->discount_code == '' ? $value->discount_code_order : $value->discount_code),
                        $value->total - $value->order_discount,
                        $value->tel_member,
                        $value->email,
                        $address,
                        $status_order2,
                    ));
                }

                $sheet->setCellValueByColumnAndRow(20, 2, "รวมทั้งหมด");
                $sheet->setCellValueByColumnAndRow(21, 2, "ส่วนลด");
                $sheet->setCellValueByColumnAndRow(22, 2, "รวมสุทธิ");


                $count_order = (int)count($orders);
                $start_row = $count_order + 3;
                $end_row = $count_order + 3;
                $area = 'A' . $start_row . ':G' . $end_row;
                $sheet->getStyle($area)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                $sheet->cell($area, function ($cell) use ($orders, $sum_order) {
                    $cell->setFont(array(
                        'bold' => true,
                        'size' => 10
                    ));
                });
                $sheet->setCellValueByColumnAndRow(20, 3, $sum_order->sum_total);
                // $sheet->setCellValueByColumnAndRow(7, $end_row, $sum_order->sum_total);

                $count_order = (int)count($orders);
                $start_row = $count_order + 4;
                $end_row = $count_order + 4;
                $area = 'A' . $start_row . ':G' . $end_row;
                $sheet->getStyle($area)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                $sheet->cell($area, function ($cell) use ($orders, $sum_order) {
                    $cell->setFont(array(
                        'bold' => true,
                        'size' => 10
                    ));
                });
                $sheet->setCellValueByColumnAndRow(21, 3, $sum_discount);
                // $sheet->setCellValueByColumnAndRow(7, $end_row, $sum_discount);

                $count_order = (int)count($orders);
                $start_row = $count_order + 5;
                $end_row = $count_order + 5;
                $area = 'A' . $start_row . ':G' . $end_row;
                $sheet->getStyle($area)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                $sheet->cell($area, function ($cell) use ($orders, $sum_order) {
                    $cell->setFont(array(
                        'bold' => true,
                        'size' => 10
                    ));
                });
                $sheet->setCellValueByColumnAndRow(22, 3, $sum_order->sum_total - $sum_discount);
                // $sheet->setCellValueByColumnAndRow(7, $end_row, $sum_order->sum_total - $sum_discount);

            });
        })->download('xlsx');
    }

    public function index()
    {
        $sum_order = DB::table('tb_order')
            ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
            ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            // ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->whereNull('tb_order.deleted_at')
            ->where('tb_order.status_order', '=', 000)
            ->first();

        $discount_details = DB::table('tb_order')
            ->select('tb_order_detail.*', 'tb_order.*', 'tb_discount.*', DB::raw('SUM(tb_order.order_discount) as total_discount'))
            ->leftjoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->leftjoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            // ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
            ->whereNull('tb_order.deleted_at')
            ->where('tb_order.status_order', '=', 000)
            // ->where('status_order','=','000')
            ->orderBy('tb_order.id', 'desc')
            ->groupBy('id')
            ->get();

        $sum_discount = 0;
        foreach ($discount_details as $discount_detail) {

            $sum_discount += $discount_detail->order_discount;
        }
        //    dd($discount_details);
        $data = array(
            'sum_order' => $sum_order,
            'sum_discount' => $sum_discount
        );
        return view('backoffice.order.index', $data);
    }

    public function showtable($m, $d, $stat)
    {
        if ($d != 'all') {
            $d2 = str_replace(' / ', ' - ', str_replace('-', '/', $d));
            $time = explode(' - ', $d2);
            $time_start = date('Y-m-d H:i:s', strtotime($time[0]));
            $time_end = date('Y-m-d H:i:s', strtotime($time[1]));
        } else {
            $time_start = '';
            $time_end = '';
        }
        $main = $m;

        if ($m == 0 && $d == "all") {
            $sum_order = DB::table('tb_order')
                ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
                ->whereNull('tb_order.deleted_at')
                ->orderBy('tb_order.id', 'desc')
                ->first();
        } elseif ($m != 0 && $d == "all") {
            $sum_order = DB::table('tb_order')
                ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
                ->orderBy('tb_order.id', 'desc')
                ->whereNull('tb_order.deleted_at')
                ->first();
        } elseif ($m == 0 && $d != "all") {
            $sum_order = DB::table('tb_order')
                ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
                ->whereNull('tb_order.deleted_at')
                ->whereBetween('tb_order.created_at', [$time_start, $time_end])
                ->orderBy('tb_order.id', 'desc')
                ->first();
        } else {
            $sum_order = DB::table('tb_order')
                ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'))
                ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
                ->where('id_main', '=', $m)
                ->orderBy('tb_order.id', 'desc')
                ->whereNull('tb_order.deleted_at')
                ->first();
        }

        $data = array(
            'sum_order' => $sum_order
        );
        return view('backoffice.order.index', $data, compact('m', 'd', 'stat'));
    }

    public function OrderSuccess()
    {


        $order_detail = DB::table('tb_order_detail')
            ->select('tb_order_detail.*', 'tb_voucher.*', 'main_voucher.*', 'system_admin.*', 'tb_order.*', DB::raw('SUM(tb_order_detail.total) as total_sales'))
            ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->where('id_admin', '=', Session::get('id_admin'))
            ->where('tb_order.status_order', '=', 000)
            ->whereNull('tb_order.deleted_at')
            ->orderBy('tb_order.id', 'desc')
            ->first();

        $discount_details = DB::table('tb_order')
            ->select('tb_order_detail.*', 'tb_order.*', 'tb_discount.*', DB::raw('SUM(tb_order.order_discount) as total_discount'))
            ->leftjoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->leftjoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->where('id_admin', '=', Session::get('id_admin'))
            ->orderBy('tb_order.id', 'desc')
            ->groupBy('id')
            ->get();
        $sum_discount = 0;
        foreach ($discount_details as $discount_detail) {
            if ($discount_detail->discount_main != 0) {
                $sum_discount += $discount_detail->order_discount;
            }
        }
        $data = array(
            'order_detail' => $order_detail,
            'sum_discount' => $sum_discount
        );
        return view('backoffice.order.index2', $data);
    }

    public function OrderUnSuccess()
    {
        $order_detail = DB::table('tb_order_detail')
            ->select('tb_order_detail.*', 'tb_voucher.*', 'main_voucher.*', 'system_admin.*', 'tb_order.*', DB::raw('SUM(tb_order_detail.total) as total_sales'))
            ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->where('id_admin', '=', Session::get('id_admin'))
            ->where('tb_order.status_order', '<>', 000)
            ->whereNull('tb_order.deleted_at')
            ->orderBy('tb_order.id', 'desc')
            ->first();
        $data = array(
            'order_detail' => $order_detail
        );
        return view('backoffice.order.index3', $data);
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

        $data = DB::table('tb_order')
            ->select(
                'tb_order.*',
                'tb_member.*',
                'tb_order.created_at as m_create',
                'tb_member.districts_id AS districtsName',
                'tb_discount.*',
                'provinces.name_th as p_name',
                'tb_member.amphures_id as a_name',
                'districts.name_th as d_name'
            )
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
            ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
            ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
            ->whereNull('tb_order.deleted_at')
            ->where('tb_order.id', '=', $id)
            ->first();

        $sum_order = DB::table('tb_order')
            ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'), 'tb_order.order_discount')
            ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            // ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->where('tb_order.id', '=', $id)
            ->groupBy('tb_order.id')
            ->first();
        // dd($sum_order);
        $logAddressOrder = LogAddressOrder::query()->where('ref_order_id',$id)->first();
        if($logAddressOrder){
            $dataAddress = json_decode($logAddressOrder->json_address);
            $getProvince = DB::table('provinces')->where('id', $dataAddress->provinces_id)->first();
            $FULLNAME = $dataAddress->name_member.' '.$dataAddress->lastname_member;
            $TEL = $dataAddress->tel_member;
            $address = $dataAddress->address_member . '     แขวง/ตำบล:  ' . $dataAddress->districts_id . ' เขต/อำเภอ: ' . ($dataAddress->amphures_id) . '    จังหวัด: ' . $getProvince->name_th . '    ' . $dataAddress->zip_code;
        }else{
            $FULLNAME = $data->name_member.' '.$data->lastname_member;
            $TEL = $data->tel_member;
            $address = $data->address_member . '     แขวง/ตำบล:  ' . ($data->d_name == '' ? $data->districtsName : $data->d_name) . ' เขต/อำเภอ: ' . ($data->a_name) . '    จังหวัด: ' . $data->p_name . '    ' . $data->zip_code;
        }
        if($data->email_order != '' && $data->email_order !== null){
            $email = $data->email_order;
        }else{
            $email = $data->email;
        }

        $data = array(
            'data' => $data,
            'sum_order' => $sum_order,
            'ADDRESS' => $address,
            'EMAIL' => $email,
            'FULLNAME' => $FULLNAME,
            'TEL' => $TEL,
        );
        return view('backoffice.order.show', $data);
    }

    public function show2($id)
    {

        $data = DB::table('tb_order')
            ->select('tb_order.*', 'tb_member.*', 'tb_order.created_at as m_create', 'tb_discount.*', 'provinces.name_th as p_name', 'tb_member.amphures_id as a_name', 'tb_member.districts_id as d_name')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
            ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
            ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
            ->whereNull('tb_order.deleted_at')
            ->where('tb_order.id', '=', $id)
            ->first();

        $sum_order = DB::table('tb_order')
            ->select(DB::raw('SUM(tb_order_detail.total) as total_sales'), 'tb_order.order_discount')
            ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            // ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->where('id_main', '=', Session::get('main_id_at'))
            ->where('tb_order.id', '=', $id)
            ->whereNull('tb_order.deleted_at')
            ->groupBy('tb_order.id')
            ->first();

        $data = array(
            'data' => $data,
            'sum_order' => $sum_order
        );
        return view('backoffice.order.show2', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['data'] = DB::table('tb_order')
            ->select('tb_order.*', 'tb_member.*', 'tb_order.created_at as m_create', 'tb_discount.*', 'provinces.name_th as p_name', 'amphures.name_th as a_name', 'districts.name_th as d_name')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
            ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
            ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
            ->where('tb_order.id', '=', $id)
            ->first();

        return view('backoffice.order.edit', $data);
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

        $order_voucher = DB::table('order_vouchers')
            ->where('order_voucher_id', $request->id)
            ->where('code_confirm', $request->code)
            ->get();
        if (count($order_voucher) != 0 && (($order_voucher[0]->stat_voucher) != 'y')) {


            $order_vouchers = DB::table('order_vouchers')
                ->where('order_voucher_id', $request->id)
                ->where('code_confirm', $request->code)
                ->first();

            $message = '<html>';
            $message .= '<head>';
            $message .= '<link href="https://fonts.googleapis.com/css?family=Prompt:400,500,700|Roboto:400,500,700" rel="stylesheet">';
            $message .= '</head>';
            $message .= '	<body>';
            $message .= '		<style>';
            $message .= '			body {';
            $message .= '			    font-family: "Roboto", "Prompt", sans-serif;';
            $message .= '			    color: #000;';
            $message .= '			    text-align: center;';
            $message .= '			}';
            $message .= '			@media print {';
            $message .= '			  body, page {';
            $message .= '			    margin: 0;';
            $message .= '			    box-shadow: 0;';
            $message .= '			  }';
            $message .= '			    .breaknewpage{';
            $message .= '			    page-break-after: always;';
            $message .= '			        }';
            $message .= '			}';
            $message .= '		</style>';

            $message .= "	<div class=\"page_container\" style=\"padding: 15px 40px; max-width: 800px; width: 100%; background: #fbdc07; font-family: 'Roboto', 'Prompt', sans-serif;
                                color: #000;\">";
            $message .= "			    <img class=\"logo\" src=\"https://www.sneaksdeal.com/img/sneakoutlogo.jpg\"  style=\"margin: 0 auto 25px auto; width: 120px; height: auto; display: block;\">";
            $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้ใช้ Voucher รหัส $order_vouchers->code_voucher<br>สามารถตรวจสอบสถานะการใช้งานได้โดยการลิงก์ หรือปุ่มด้านล่าง</div>";
            $message .= "			    <div style=\"font-size: 20px; text-align: center; margin: 30px 0;\">";
            $message .= "			        <a style=\"display:inline-block;color:black;\" href=\"https://www.sneaksdeal.com/order-detail/$order_vouchers->orders_id\">คลิกที่นี่</a>";
            $message .= '			    </div>';
            $message .= "			    <div style=\"display:block; text-align:center; margin-top:15px; margin-bottom:15px;\">
                                        <a href=\"tel:+6667701732\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                                            <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/icon02.png\">
                                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">08-677-01732</span>
                                        </a>
                                        <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                                            <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
                                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">Sneaksdeal</span>
                                        </a>
                                        <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
                                            <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
                                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">sneakoutclub@gmail.com</span>
                                        </a>
                                     
                                    </div>";
            $message .= "			    <div style=\"border-bottom: 1px dashed #ccc; margin-top: 15px; margin-bottom: 25px;\"></div>";
            $message .= "			    <div style=\"font-size: 11px; text-align: center;\">";
            $message .= '			        <p>117 ถนนแฉล้มนิมิตร แขวงบางโคล่ เขตบางคอแหลม กรุงเทพ 10120</p>';
            $message .= '			        <p>เว็บไซต์ : https://www.sneaksdeal.com</p>';
            $message .= '			    </div>';
            $message .= '			</div>';
            $message .= '	</body>';
            $message .= '</html>';

            try {
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->IsHTML(true);
                $mail->SMTPDebug = 0;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "tls";
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 587;
                $mail->CharSet = "utf-8";
                $mail->SetFrom(Session::get('email'), Session::get('name_member') . ' ' . Session::get('lastname_member'));
                $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
                $mail->Password = 'sneaksdeal1234';       //Pass Sent
                $mail->From = 'sneaksdeal2018@gmail.com';    //User
                $mail->FromName = "Sneaksdeal";
                $mail->Subject = "รายละเอียดการใช้งาน Voucher   จาก https://sneaksdeal.com ";
                $mail->Body = $message;
                $mail->AddAddress($request->email_send);
                $mail->set('X-Priority', '3');
                $mail->Send();
            } catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
            $today = date("Y-m-d H:i:s");
            $order_voucher = DB::table('order_vouchers')
                ->where('order_voucher_id', $request->id)
                ->where('code_confirm', $request->code)
                ->update([
                    'stat_voucher' => 'y',
                    'use_date' => $today
                ]);
            echo 1;
        } else {
            echo 2;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Orders::destroy($id);
    }


    public function queryDatatable(Request $request, $m, $d)
    {

        //dd($time_start);
        if ($d != 'all') {
            $d2 = str_replace(' / ', ' - ', str_replace('-', '/', $d));
            $time = explode(' - ', $d2);
            $time_start = date('Y-m-d H:i:s', strtotime($time[0]));
            $time_end = date('Y-m-d H:i:s', strtotime($time[1]));
        } else {
            $time_start = '';
            $time_end = '';
        }
        if ($m == 0 && $d == "all") {
            $data = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as m_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'main_voucher.name_main'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->whereNull('tb_order.deleted_at')
                ->orderBy('tb_order.id', 'DESC');

            // $data = Order_voucher::leftjoin('tb_order','order_vouchers.orders_id','=','tb_order.id')
            //         ->select('tb_order.*', 'tb_order_detail.*', 'tb_member.*', 'tb_order.created_at as m_create', 'tb_discount.*', 'order_vouchers.*', 'tb_member.*', 'main_voucher.*')
            //         ->leftjoin('tb_order_detail','order_vouchers.order_detail_id','=','tb_order_detail.odt_id')
            //         ->leftjoin('main_voucher','tb_order_detail.main_id','=','main_voucher.id_main')
            //         ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            //         ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            //         ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
            //         ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            //         ->whereNull('tb_order.deleted_at')
            //         ->orderBy('tb_order.id','desc')
            //         ->get();
            //
            //            $discount_details = DB::table('tb_order')
            //                ->select('tb_order_detail.*', 'tb_order.*', 'tb_discount.*', DB::raw('SUM(tb_order.order_discount) as total_discount'))
            //                ->leftjoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            //                ->leftjoin('tb_discount', 'tb_order_detail.discount', '=', 'tb_discount.discount_id')
            //                ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            //                ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            //                // ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
            //                ->whereNull('tb_order.deleted_at')
            //                ->where('tb_order.status_order', '=', 000)
            //                // ->where('status_order','=','000')
            //                ->orderBy('tb_order.id', 'desc')
            //                ->groupBy('id')
            //                ->get();
            //
            //            $sum_discount = 0;
            //            foreach ($discount_details as $discount_detail) {
            //
            //                $sum_discount += $discount_detail->order_discount;
            //
            //
            //            }
        } elseif ($m != 0 && $d == "all") {

            $data = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as m_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'main_voucher.name_main'
                )->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->whereNull('tb_order.deleted_at')
                ->where('id_main', '=', $m)
                ->orderBy('tb_order.id', 'desc');
        } elseif ($m == 0 && $d != "all") {
            $data = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as m_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'main_voucher.name_main'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->whereBetween('tb_order.created_at', [$time_start, $time_end])
                ->whereNull('tb_order.deleted_at')
                ->orderBy('tb_order.id', 'desc');
        } else {

            $data = Order_details::query()
                ->select(
                    'tb_order.*',
                    'tb_order.id AS id_order',
                    'tb_order.created_at as m_create',
                    'tb_order_detail.priceper',
                    'tb_discount.discount_id AS id_discount',
                    'tb_discount.discount_code',
                    'order_vouchers.stat_voucher',
                    'order_vouchers.use_date',
                    'order_vouchers.order_voucher_id',
                    'order_vouchers.pay_status',
                    'order_vouchers.pay_by',
                    'order_vouchers.code_voucher',
                    'tb_member.name_member',
                    'tb_member.lastname_member',
                    'main_voucher.name_main'
                )
                ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
                ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->whereNull('tb_order.deleted_at')
                ->where('id_main', '=', $m)
                ->orderBy('tb_order.id', 'desc');
        }

        $status = $request->status;
        if ($status == 'no') {
            $data = $data->whereNotIn('tb_order.status_order', ['001', '002', '000']);
        } else if ($status == 'all') {
        } else {
            $data = $data->where('tb_order.status_order', $status);
        }
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('id', function ($data) {
                return str_pad(@$data->id_order, 11, "0", STR_PAD_LEFT);
                //                return $data->id_order;
            })
            ->addColumn('user_id', function ($data) {
                return @$data->name_member . ' ' . @$data->lastname_member;
            })
            ->addColumn('status_payment', function ($data) {

                $status_payment2 = substr(@$data->status_payment, 2, 1);

                if ($status_payment2 == 1) {
                    $status_payment = 'บัตรเครดิตวีซ่า / มาสเตอร์การ์ด';
                } else if ($status_payment2 == 2) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์';
                } else if ($status_payment2 == 3) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์ธนาคาร';
                } else {
                    $status_payment = 'ชําระเงินผ่านตู้ atm';
                }
                return $status_payment;
            })
            ->addColumn('pay_status', function ($data) {
                $pay_status = @$data->pay_status;
                $pay_by = @$data->pay_by;
                $pay_by2 = DB::table('system_admin')->where('id_admin', '=', $pay_by)->first();
                if ($pay_status == 0) {
                    return $pay_status = '<center><label class="switch">
                            <input type="checkbox" onchange="changeShowFilter($(this))" name="show" value="' . @$data->order_voucher_id . '" ' . ($pay_status == 1 ? 'checked' : '') . ' id="switch">
                            <span class="slider round"></span>
                        </label></center>';
                } else {
                    //     return $pay_status = '<center><label class="switch">
                    //     <input type="checkbox" onchange="changeShowFilter($(this))" name="show" value="' . $data->id . '" ' . ($pay_status == 1 ? 'checked' : '') . ' id="switch">
                    //     <span class="slider round"></span>
                    // </label></center>';
                    return $pay_status = "<br/>" . "<span class='text-success'>ชำระเงินแล้ว</span>" . "<br/>" . "โดย " . @$pay_by2->name_admin . ' ' . @$pay_by2->lastname_admin;
                }
            })
            ->addColumn('status_order3', function ($data) {
            $status_order = @$data->status_order;
            if ($status_order == "000") {
                $status_order2 = "<span class='text-success'>ชำระเงินแล้ว</span>";
            } else if ($status_order == "001") {
                $status_order2 = "<span class='text-warning'>กำลังดำเนินการ</span>";
            } else if ($status_order == "002") {
                $status_order2 = "<span class='text-warning'>ชำระเงินล้มเหลว</span>";
            } else {
                $status_order2 = "<span class='text-danger'>ยกเลิกการชำระเงิน</span>";
            }

                return $status_order2;
            })
            ->addColumn('use_date', function ($data) {

                if (@$data->stat_voucher == "n") {
                    $use_date = "-";
                } else {
                    $use_date = @$data->use_date;
                }

                return $use_date;
            })
            ->addColumn('stat_voucher', function ($data) {

                if (@$data->stat_voucher == "n") {
                    $stat_voucher = "<span class='text-danger'>ยังไม่ได้ใช้งาน</span>";
                } else {
                    $stat_voucher = "<span class='text-success'>ใช้งานแล้ว</span>";
                }

                return $stat_voucher;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_order, true, false, true, '/backoffice/order');
                return $Manage;
            })
            ->addColumn('SendVoucherScan', function ($data) {
                $txt = '<center><label class="switch">
                                <input type="checkbox" ' . ($data->status_send_voucher == 'scan' || $data->status_send_voucher == 'true' ? 'disabled' : '') . ' onchange="changeSendVoucher($(this))" ' . ($data->status_send_voucher == 'scan' || $data->status_send_voucher == 'true' ? 'checked' : '') . ' name="sendVoucher" value="' . $data->id_order . '"  ">
                                <span class="slider round"></span>
                            </label></center>';
                return $txt;
            })
            ->addColumn('SendVoucherTrue', function ($data) {
                if ($data->status_send_voucher == 'scan') {
                    $txt = '<center>
             <a title="edit" href="javascript:void(0)" onclick="viewSendVoucher2(' . $data->id_order . ')" class="btn btn-flat btn-outline-warning ">
             <i class="far fa-edit"></i></a>
             </center>';
                } else if ($data->status_send_voucher == 'true') {
                    $txt = 'Tracking No.' . $data->tracking_no;
                } else {
                    $txt = 'กดยืนยันแบบ Scan ก่อน';
                }
                return $txt;
            })
            ->rawColumns(['No', 'SendVoucherTrue', 'SendVoucherScan', 'status_payment', 'user_id', 'use_date', 'pay_status', 'status_order3', 'stat_voucher', 'Manage'])
            ->make(true);
    }

    public function queryDatatable4()
    {
        $data = Order_details::query()
            ->select(
                'tb_order.*',
                'tb_order.id AS id_order',
                'tb_order.created_at as m_create',
                'tb_order_detail.priceper',
                'tb_discount.discount_id AS id_discount',
                'tb_discount.discount_code',
                'order_vouchers.stat_voucher',
                'order_vouchers.use_date',
                'order_vouchers.order_voucher_id',
                'order_vouchers.pay_status',
                'order_vouchers.pay_by',
                'order_vouchers.code_voucher',
                'tb_member.name_member',
                'tb_member.lastname_member',
                'main_voucher.name_main'
            )
            ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
            ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->whereNull('tb_order.deleted_at')
            ->orderBy('tb_order.id', 'DESC');
        // dd($data);
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('id', function ($data) {
                return str_pad($data->id, 11, "0", STR_PAD_LEFT);
            })
            ->addColumn('user_id', function ($data) {
                return $data->name_member . ' ' . $data->lastname_member;
            })
            ->addColumn('status_payment', function ($data) {

                $status_payment2 = substr($data->status_payment, 2, 1);

                if ($status_payment2 == 1) {
                    $status_payment = 'บัตรเครดิตวีซ่า / มาสเตอร์การ์ด';
                } else if ($status_payment2 == 2) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์';
                } else if ($status_payment2 == 3) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์ธนาคาร';
                } else {
                    $status_payment = 'ชําระเงินผ่านตู้ atm';
                }
                return $status_payment;
            })
            ->addColumn('SendVoucherScan', function ($data) {
                $txt = '<center><label class="switch">
                                <input type="checkbox" ' . ($data->status_send_voucher == 'scan' || $data->status_send_voucher == 'true' ? 'disabled' : '') . ' onchange="changeSendVoucher($(this))" '.($data->status_send_voucher == 'scan' || $data->status_send_voucher == 'true' ?'checked':'').' name="sendVoucher" value="' . $data->id_order . '"  ">
                                <span class="slider round"></span>
                            </label></center>';
                return $txt;
            })
            ->addColumn('SendVoucherTrue', function ($data) {
                if($data->status_send_voucher == 'scan'){
            $txt = '<center>
             <a title="edit" href="javascript:void(0)" onclick="viewSendVoucher2('.$data->id_order.')" class="btn btn-flat btn-outline-warning ">
             <i class="far fa-edit"></i></a>
             </center>';
                }else if($data->status_send_voucher == 'true'){
                    $txt = 'Tracking No.'.$data->tracking_no;
                }else{
                    $txt = 'กดยืนยันแบบ Scan ก่อน';
                }
                return $txt;
            })
            ->addColumn('pay_status', function ($data) {
                $pay_status = $data->pay_status;
                $pay_by = $data->pay_by;
                $pay_by2 = DB::table('system_admin')->where('id_admin', '=', $pay_by)->first();
                if ($pay_status == 0) {
                    return $pay_status = '<center><label class="switch">
                                <input type="checkbox" onchange="changeShowFilter($(this))" name="show" value="' . $data->order_voucher_id . '" ' . ($pay_status == 1 ? 'checked' : '') . ' id="switch">
                                <span class="slider round"></span>
                            </label></center>';
                } else {
                    //     return $pay_status = '<center><label class="switch">
                    //     <input type="checkbox" onchange="changeShowFilter($(this))" name="show" value="' . $data->id . '" ' . ($pay_status == 1 ? 'checked' : '') . ' id="switch">
                    //     <span class="slider round"></span>
                    // </label></center>';
                    return $pay_status = "<br/>" . "<span class='text-success'>ชำระเงินแล้ว</span>" . "<br/>" . "โดย " . $pay_by2->name_admin . ' ' . $pay_by2->lastname_admin;
                }
            })
            ->addColumn('status_order3', function ($data) {
                $status_order = @$data->status_order;
                if ($status_order == "000") {
                    $status_order2 = "<span class='text-success'>ชำระเงินแล้ว</span>";
                } else if ($status_order == "001") {
                    $status_order2 = "<span class='text-warning'>กำลังดำเนินการ</span>";
                } else if ($status_order == "002") {
                    $status_order2 = "<span class='text-warning'>ชำระเงินล้มเหลว</span>";
                } else {
                    $status_order2 = "<span class='text-danger'>ยกเลิกการชำระเงิน</span>";
                }

                return $status_order2;
            })
            ->addColumn('use_date', function ($data) {

                if ($data->stat_voucher == "n") {
                    $use_date = "-";
                } else {
                    $use_date = $data->use_date;
                }

                return $use_date;
            })
            ->addColumn('stat_voucher', function ($data) {

                if ($data->stat_voucher == "n") {
                    $stat_voucher = "<span class='text-danger'>ยังไม่ได้ใช้งาน</span>";
                } else {
                    $stat_voucher = "<span class='text-success'>ใช้งานแล้ว</span>";
                }

                return $stat_voucher;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, true, false, true, '/backoffice/order');
                return $Manage;
            })
            ->rawColumns(['No', 'status_payment', 'user_id', 'use_date', 'pay_status', 'SendVoucherTrue', 'SendVoucherScan', 'status_order3', 'stat_voucher', 'Manage'])
            ->make(true);
    }

    public function queryDatatable2()
    {
        $data = DB::table('tb_order_detail')
            ->select('tb_order.*', 'tb_order.id as o_id', 'tb_order_detail.*', 'tb_member.*', 'tb_order.created_at as m_create', 'tb_discount.*', 'order_vouchers.*', 'tb_member.*', 'main_voucher.*', 'tb_voucher.*')
            ->leftJoin('order_vouchers', 'tb_order_detail.odt_id', '=', 'order_vouchers.order_detail_id')
            ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->leftJoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->where('id_admin', '=', Session::get('id_admin'))
            ->where('tb_order.status_order', '=', 000)
            ->whereNull('tb_order.deleted_at')
            ->orderBy('tb_order.id', 'desc')
            ->get();

        // $data = DB::table('tb_order')
        //         ->select('tb_order.*','tb_order.id as o_id','tb_member.*','tb_order.created_at as m_create','tb_discount.*')
        //         ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
        //         ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
        //         ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
        //         ->leftjoin('tb_voucher','tb_order_detail.voucher_id','=','tb_voucher.voucher_id')
        //         ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
        //         ->leftjoin('system_admin','main_voucher.id_main','=','system_admin.main_id_at')
        //         ->where('id_admin','=',Session::get('id_admin'))
        //         ->where('tb_order.status_order', '=', 000)
        //         ->groupBy('tb_order_detail.order_id')
        //         ->whereNull('tb_order.deleted_at')
        //         ->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('user_id', function ($data) {
                return $data->name_member . ' ' . $data->lastname_member;
            })
            ->addColumn('status_order', function ($data) {
                if ((($data->status_order) == 00) || (($data->status_order) == 000)) {
                    return "สำเร็จ";
                } else {
                    return "กำลังดำเนินการ";
                }
            })
            ->addColumn('status_payment', function ($data) {

                $status_payment2 = substr($data->status_payment, 0, 1);
                if ($status_payment2 == 1) {
                    $status_payment = 'บัตรเครดิตวีซ่า / มาสเตอร์การ์ด';
                } else if ($status_payment2 == 2) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์';
                } else if ($status_payment2 == 3) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์ธนาคาร';
                } else {
                    $status_payment = 'ชําระเงินผ่านตู้ atm';
                }
                return $status_payment;
            })
            ->addColumn('discount_id', function ($data) {
                if ($data->discount_id != "") {
                    return $data->discount_code;
                } else {
                    return "-";
                }
            })
            ->addColumn('stat_voucher', function ($data) {
                if ($data->stat_voucher == "y") {
                    return "<span class='text-success'>ใช้งานแล้ว</span>";
                } else {
                    return "<span class='text-danger'>ยังไม่ได้ใช้งาน</span>";
                }
            })
            ->addColumn('use_date', function ($data) {
                if ($data->stat_voucher == "n") {
                    $use_date = "-";
                } else {
                    $use_date = $data->use_date;
                }

                return $use_date;
            })
            ->addColumn('pay_status', function ($data) {
                $pay_status = $data->pay_status;
                if ($pay_status == 1) {
                    $pay_status2 = "<span class='text-success'>ชำระเงินแล้ว</span>";
                } else {
                    $pay_status2 = "<span class='text-danger'>ยังไม่ได้ชำระ</span>";
                }

                return $pay_status2;
            })
            ->addColumn('order_total', function ($data) {
            })
            ->addColumn('edit_status', function ($data) {

                if ($data->stat_voucher == 'n') {
                    return '<input type="hidden" name="email_send" id="email_send"  value="' . $data->email . '"/> <button onclick="editData(' . $data->order_voucher_id . ')"  class="btn btn-success show_modal" id="show_modal" atr="' . $data->order_voucher_id . '"  data-toggle="modal" data-target="#exampleModal"><i class="fas fa-check-circle"></i> ใช้งาน</button>';
                } else {
                    return '<input type="hidden" name="email_send" id="email_send" value="' . $data->email . '"/> <button class="btn btn-danger show_modal " id="show_modal" atr="' . $data->order_voucher_id . '"  data-toggle="modal" data-target="#exampleModal" disabled><i class="fas fa-times-circle"></i> ใช้งานแล้ว</button>';
                }
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, true, false, false, 'backoffice/order');
                return $Manage;
            })
            ->rawColumns(['No', 'user_id', 'discount_id', 'stat_voucher', 'pay_status', 'edit_status', 'Manage'])
            ->make(true);
    }

    public function queryDatatable3()
    {
        $data = DB::table('tb_order')
            ->select('tb_order.*', 'tb_order.id as o_id', 'tb_member.*', 'tb_order.created_at as m_create', 'tb_discount.*')
            ->leftJoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->leftJoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->leftjoin('system_admin', 'main_voucher.id_main', '=', 'system_admin.main_id_at')
            ->where('id_admin', '=', Session::get('id_admin'))
            ->where('tb_order.status_order', '<>', 000)
            ->whereNull('tb_order.deleted_at')
            ->orderBy('tb_order.id', 'desc')
            ->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('user_id', function ($data) {
                return $data->name_member . ' ' . $data->lastname_member;
            })
            ->addColumn('status_order', function ($data) {
                if ((($data->status_order) == 00) || (($data->status_order) == 000)) {
                    return "สำเร็จ";
                } else {
                    return "กำลังดำเนินการ";
                }
            })
            ->addColumn('status_payment', function ($data) {
                $status_payment2 = substr($data->status_payment, 0, 1);
                if ($status_payment2 == 1) {
                    $status_payment = 'บัตรเครดิตวีซ่า / มาสเตอร์การ์ด';
                } else if ($status_payment2 == 2) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์';
                } else if ($status_payment2 == 3) {
                    $status_payment = 'ชำระเงินผ่านเคาน์เตอร์ธนาคาร';
                } else {
                    $status_payment = 'ชําระเงินผ่านตู้ atm';
                }
                return $status_payment;
            })
            ->addColumn('discount_id', function ($data) {
                if ($data->discount_id != "") {
                    return $data->discount_code;
                } else {
                    return "-";
                }
            })
            ->addColumn('order_total', function ($data) {
                return $data->order_total;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, true, false, true, 'backoffice/order');
                return $Manage;
            })
            ->rawColumns(['No', 'user_id', 'Manage'])
            ->make(true);
    }

    public function ChangeOrder(Request $request, $id)
    {
        // echo Session::get('id_admin');
        $stat = $request->stat;
        $up = DB::table('order_vouchers')
            ->where('order_voucher_id', $id)
            ->update([
                'pay_status' => $stat,
                'pay_by' => Session::get('id_admin')
            ]);

        if ($up) {
            messageSuccess('เปลี่ยนสถานะสำเร็จ');
        } else {
            messageError('เปลี่ยนสถานะล้มเหลว');
        }
    }
    
    public function changeSendVoucher(Request $request, $id){
        Orders::query()->where('id',$id)->update(['status_send_voucher'=>'scan']);
        messageSuccess('อัพเดทสถาน Voucher สำเร็จ');

    }

    public function viewSendVoucherTrue($id){

        return view('backoffice.order.show_form_voucher', compact('id'));
    }
   
    public function SaveSendVoucherTrue(Request $request, $id){
        $trackingNo = $request->trackingNo;
        Orders::query()->where('id', $id)->update(['status_send_voucher' => 'true', 'tracking_no'=> $trackingNo]);
        return redirect('/backoffice/order')->with('success','อัพเดทเรียบร้อย');
    }
}
