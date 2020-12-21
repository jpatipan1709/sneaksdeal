<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use Session;
use Cart;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DB;
use App\Voucher;
use App\Model\LogPayment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.payment');
    }


    public function store(Request $request)
    {
        $payload = \Payment2C2P::paymentRequest([
            'desc' => '1 room for 2 nights',
            'uniqueTransactionCode' => "Invoice" . time(),
            'amt' => '1000000',
            'currencyCode' => '702',
            'cardholderName' => 'Card holder Name',
            'cardholderEmail' => 'email@emailcom',
            'panCountry' => 'SG',
            'encCardData' => $request->input('encryptedCardInfo'), // Retrieve encrypted credit card data 
            'userDefined1' => 'userDefined1',
            'userDefined2' => 'userDefined2'
        ]);
        return view('pages.endpayment', compact('payload'));
    }


    public function show(Request $request, $id)
    {
        $payload = \Payment2C2P::paymentRequest([
            'desc' => '1 room for 2 nights',
            'uniqueTransactionCode' => "Invoice" . time(),
            'amt' => '1000000',
            'currencyCode' => '702',
            'cardholderName' => 'Card holder Name',
            'cardholderEmail' => 'email@emailcom',
            'panCountry' => 'SG',
            'encCardData' => $request->input('encryptedCardInfo'), // Retrieve encrypted credit card data 
            'userDefined1' => '764764000001527',
            'userDefined2' => 'E2DABDC84FFD2A5B378DC72F4AA130A39603C2498CA8C7AF63072A9E163F33DD'
        ]);
        dd($payload);
        return view('pages.payment', compact('payload'));
    }

    public function backEndPayment(Request $request)
    {
        $orderId = @$request->order_id;
        $create = new LogPayment();
        $create->jsonData = json_encode($request->all());
        $create->save();
        $member = DB::table('tb_member')
            ->leftjoin('tb_order', 'tb_member.id_member', '=', 'tb_order.user_id')
            ->where('tb_order.id', '=', $orderId)
            ->first();

        session(['email' => $member->email]);
        session(['name_member' => $member->name_member]);
        session(['lastname_member' => $member->lastname_member]);
        session(['id_member' => $member->id_member]);


        $token = str_random(25);
        Orders::where('id', $request->order_id)
            ->update([
                'status_order' => $request->payment_status,
                'status_payment' => $request->payment_channel,
                'comfirm_payment' => $token
            ]);

        $order = DB::table('tb_order')
            ->select('tb_order.*', 'tb_discount.*', 'tb_order.created_at AS time_created')
            ->leftjoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->where('id', '=', $request->order_id)
            ->first();

        $vouchers = DB::table('tb_voucher')
            ->leftjoin('tb_order_detail', 'tb_voucher.voucher_id', '=', 'tb_order_detail.voucher_id')
            ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->where('tb_order.id', '=', $request->order_id)->get();

        $order_vouchers = DB::table('order_vouchers')
            ->leftjoin('tb_voucher', 'order_vouchers.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->where('orders_id', '=', $request->order_id)->get();


        $data = ['orderId' => $orderId];
        $data['order'] = $order;
        $data['vouchers'] = $vouchers;
        $data['email_order'] = $order->email_order;
        $data['name_member'] = Session::get('name_member') . ' ' . Session::get('lastname_member');
        $data['order_vouchers'] = $order_vouchers;
//dd(array($data));
        if ($order->mail_status != 'yes' && $request->payment_status == 000) {
            Mail::send(['html' => 'email.voucher'], (array)$data, function ($message) use ($data) {
                $message->to($data['email_order'], $data['name_member'])
                    ->subject('รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่  ' . $data['orderId'] . '  จาก sneaksdeal.com ');
                $message->from('info@sneaksdeal.com', 'sndeaksdeal.com');
            });
            Orders::query()->where('id', $order->id)->update(['mail_status' => 'yes']);
        }
//            $message = '<html>';
//            $message .= '<head>';
//            $message .= '<link href="https://fonts.googleapis.com/css?family=Prompt:400,500,700|Roboto:400,500,700" rel="stylesheet">';
//            $message .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css" />';
//
//            $message .= '	<body>';
//            $message .= '		<style>';
//            $message .= '			body {';
//            $message .= '			    font-family: "Roboto", "Prompt", sans-serif;';
//            $message .= '			    color: #000;';
//            $message .= '			    text-align: center;';
//            $message .= '			}';
//            $message .= '			@media print {';
//            $message .= '			  body, page {';
//            $message .= '			    margin: 0;';
//            $message .= '			    box-shadow: 0;';
//            $message .= '			  }';
//            $message .= '			    .breaknewpage{';
//            $message .= '			    page-break-after: always;';
//            $message .= '			        }';
//            $message .= '			}';
//            $message .= '		</style>';
//
//            $message .= "	<div class=\"page_container\" style=\"padding: 15px 40px; max-width: 800px;min-width: 800px; width: 100%; background: #fbdc07; font-family: 'Roboto', 'Prompt', sans-serif;
//                color: #000;\">";
//            $message .= "			    <img class=\"logo\" src=\"https://www.sneaksdeal.com/img/sneakoutlogo.jpg\"  style=\"margin: 0 auto 25px auto; width: 120px; height: auto; display: block;\">";
//            $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้สั่งซื้อสินค้า ตามรหัสการสั่งซื้อ เลขที่ $request->order_id </div>";
//            $message .= "
//                                        <div class=\"bg_white breaknewpage\" style=\"     background-color: #FFF;
//                                    padding: 15px;
//                                    max-width: 800px;
//                                    min-width: 800px;
//                                    margin-top: 10px;\">
//                                            <div class=\"head_order\" style=\"font-size: 18px;
//                                    font-weight: 500;
//
//                                    width: 48%;
//                                    display: inline-block;
//                                    vertical-align: top; \">
//
//
//                                            </div>
//                                    <div class=\"time_order\" style=\"
//                                    max-width: 740px;
//                                    min-width: 740px;
//                                    display: inline-block;
//                                    font-size: 13px;
//                                    text-align: right;
//                                    vertical-align: top;\">
//                                                Date " . DateThai($order->time_created, 'd/m/Y') . "
//                                            </div>
//                                            <div style=\"width:50%; text-align: left; font-size:18px; font-weight: 500; \">
//
//                                            <table class=\"table_order table-responsive\" style=\"    font-size: 15px;
//                                    width: 100%;
//                                    max-width: 740px;
//                                    min-width: 740px;
//                                    margin-top: 15px;
//                                    border-collapse: collapse; \">
//                                                <tr class=\"bg_gd\" style=\"     background: #325b87;\">
//                                                    <th style=\"    font-weight: 400;
//                                    text-align: center;
//                                    color: #FFF;
//                                    padding-bottom: 5px;
//                                    padding-top: 5px; \" colspan=\"3\">สินค้า<span style=\"font-size: 11px;
//                                    display: block; \">Voucher Description</span></th>
//                                                    <th style=\"    font-weight: 400;
//                                    text-align: center;
//                                    color: #FFF;
//                                    padding-bottom: 5px;
//                                    padding-top: 5px; \">หน่วย<span style=\"font-size: 11px;
//                                    display: block; \">Unit</span></th>
//                                                    <th style=\"    font-weight: 400;
//                                    text-align: center;
//                                    color: #FFF;
//                                    padding-bottom: 5px;
//                                    padding-top: 5px; \">ราคาต่อหน่วย<span style=\"font-size: 11px;
//                                    display: block; \">Price/Unit</span></th>
//
//                                                </tr>";
//
//            $sumtotal = 0;
//            foreach ($vouchers as $key => $voucher) {
//                $total = $voucher->priceper * $voucher->qty;
//                $sumtotal += $total;
//                $key += 1;
//                $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
//                                                                            <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" colspan=\"3\">
//                                                                                $voucher->name_main  : $voucher->name_voucher
//                                                                            </td>
//                                                                            <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;
//                                                font-size: 13px;     text-align: center; \">
//                                                                                {$voucher->qty}
//                                                                            </td>
//                                                                            <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;
//                                                font-size: 13px;     text-align: center; \">
//                                                                                {$voucher->priceper}
//                                                                            </td>
//
//                                                                        </tr>";
//            }
//            if ($order->discount_main != 0) {
//                $total = $sumtotal - $order->discount_bath;
//                $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
//                                                <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" colspan=\"3\">
//
//                                                </td>
//                                                <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px; font-size: 13px;     text-align: center; \">
//                                                                                รหัสพิเศษ
//                                                </td>
//                                                                            <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;
//                                                font-size: 13px;     text-align: center; \">
//                                                                                {$order->discount_code}
//                                                                            </td>
//
//                                                </tr>";
//
//                $message .= "<tr class=\"borderdot\" style=\" \">
//                                                                    <td style=\"    padding-top: 15px;
//                                                    border-bottom: 1px solid #eee; \" colspan=\"8\"></td>
//                                                                </tr>
//                                                                <tr style=\" \">
//                                                                    <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\"></td>
//
//                                                                    <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;    text-align: right;\" class=\"num_total\"></td>
//                                                                </tr>
//                                                            </table>
//                                                        </div>";
//            } else {
//
//                $message .= "<tr class=\"borderdot\" style=\" \">
//                                                <td style=\"    padding-top: 15px;
//                                                border-bottom: 1px solid #eee; \" colspan=\"8\"></td>
//                                                            </tr>
//                                                            <tr style=\" \">
//                                                                <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\"></td>
//
//                                                                <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;    text-align: right;\" class=\"num_total\"></td>
//                                                            </tr>
//                                                        </table>
//                                                    </div>";
//            }
//
//            $message .= '			    </div>';
//            $message .= "
//                                        <div class=\"bg_white breaknewpage\" style=\"     background-color: #FFF;
//                                        max-width: 800px;
//                                        min-width: 800px;
//                                    padding: 15px;
//                                    margin-top: 10px;\">
//
//                                            <div style=\"width:50%; text-align: left; font-size:18px; font-weight: 500; \">
//                                                รายละเอียด Voucher ตามรายการสั่งซื้อ
//                                            <table class=\"table_order\" style=\"    font-size: 15px; width: 740px;max-width: 740px;min-width: 740px;margin-top: 15px;border-collapse: collapse; \" border=\"1\">
//                                                <tr class=\"bg_gd\">
//                                                    <th style=\"    font-weight: 400;text-align: center;color: black;padding-bottom: 5px;padding-top: 5px; \" >#</th>
//                                                    <th style=\"    font-weight: 400;text-align: center;color: black;padding-bottom: 5px;padding-top: 5px; \">ชื่อ</th>
//                                                    <th style=\"    font-weight: 400;text-align: center;color: black;padding-bottom: 5px;padding-top: 5px; \">รหัส</th>
//                                                    <th style=\"    font-weight: 400;text-align: center; color: black;padding-bottom: 5px;padding-top: 5px; \">รหัสยืนยัน</th>
//                                                </tr>";
//            foreach ($order_vouchers as $key => $order_voucher) {
//                $key += 1;
//                $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
//                                                    <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" >{$key}</td>
//                                                    <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">$order_voucher->name_voucher</td>
//                                                    <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">{$order_voucher->code_voucher}</td>
//                                                    <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">{$order_voucher->code_confirm}</td>
//                                                </tr>";
//            }
//            $message .= "
//                                            </table>
//                                            </div>";
//            $message .= '			    </div>';
//            $message .= "			    <div style=\"display:block; text-align:center; margin-top:15px; margin-bottom:15px;\">
//                        <a href=\"tel:+6667701732\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
//                            <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon02.png\">
//                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">091-718-4432</span>
//                        </a>
//                        <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
//                            <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
//                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">Sneak Out หนีเที่ยว</span>
//                        </a>
//                        <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
//                            <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
//                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">info.sneaksdeal@gmail.com</span>
//                        </a>
//
//                    </div>";
//            $message .= "			    <div style=\"border-bottom: 1px dashed #ccc; margin-top: 15px; margin-bottom: 25px;\"></div>";
//            $message .= "			    <div style=\"font-size: 11px; text-align: center;\">";
//            $message .= '			        <p>117 ถนนแฉล้มนิมิตร แขวงบางโคล่ เขตบางคอแหลม กรุงเทพ 10120</p>';
//            $message .= '			        <p>เว็บไซต์ : https://www.sneaksdeal.com</p>';
//            $message .= '			    </div>';
//            $message .= '			</div>';
//            $message .= '	</body>';
//            $message .= '</html>';
//
//
//            // try {
//            $mail = new PHPMailer();
//            $mail->IsSMTP();
//            $mail->IsHTML(true);
//            $mail->SMTPDebug = 0;
//            $mail->SMTPAuth = true;
//            $mail->SMTPSecure = "tls";
//            $mail->Host = "smtp.gmail.com";
//            $mail->Port = 587;
//            $mail->CharSet = "utf-8";
//            $mail->SetFrom($member->email, $member->name_member . ' ' . $member->lastname_member);
//            $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
//            $mail->Password = 'sneaksdeal1234';       //Pass Sent
//            $mail->From = 'sneaksdeal2018@gmail.com';    //User
//            $mail->FromName = "https://sneaksdeal.com";
//            $mail->Subject = "รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่  $request->order_id  จาก https://sneaksdeal.com ";
//            $mail->Body = $message;
//            $mail->AddAddress($member->email);
//            $mail->set('X-Priority', '3');
//            // dd(count($vouchers));
//            if (count($vouchers) > 0) {
//                $mail->Send();
//            }

//        dd('success');
    }

    public function redirected(Request $request)
    {
        $member = DB::table('tb_member')
            ->leftjoin('tb_order', 'tb_member.id_member', '=', 'tb_order.user_id')
            ->where('tb_order.id', '=', $request->order_id)
            ->first();

        session(['email' => $member->email]);
        session(['name_member' => $member->name_member]);
        session(['lastname_member' => $member->lastname_member]);
        session(['id_member' => $member->id_member]);

        function DateThai($strDate)
        {
            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("j", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
        }

        $token = str_random(25);
        Orders::where('id', $request->order_id)
            ->update([
                'status_order' => $request->payment_status,
                'status_payment' => $request->payment_channel,
                'comfirm_payment' => $token
            ]);

        $order = DB::table('tb_order')
            ->select('tb_order.*', 'tb_discount.*', 'tb_order.created_at AS time_created')
            ->leftjoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->where('id', '=', $request->order_id)
            ->first();
        $vouchers = DB::table('tb_voucher')
            ->leftjoin('tb_order_detail', 'tb_voucher.voucher_id', '=', 'tb_order_detail.voucher_id')
            ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->where('tb_order.id', '=', $request->order_id)->get();
        $order_vouchers = DB::table('order_vouchers')
            ->leftjoin('tb_voucher', 'order_vouchers.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->where('orders_id', '=', $request->order_id)->get();

        if ($request->payment_status == '000' && $order->mail_status != 'yes') {

            $data = ['orderId' => $request->order_id];
            $data['mail_to'] = $order->email_order;
            $data['name_to'] = Session::get('name_member') . ' ' . Session::get('lastname_member');
            $data['order'] = $order;
            $data['vouchers'] = $vouchers;
            $data['order_vouchers'] = $order_vouchers;
//dd(array($data));
            Mail::send(['html' => 'email.voucher'], (array)$data, function ($message) use ($data) {
                $message->to($data['mail_to'], $data['name_to'])
                    ->subject('รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่  ' . $data['orderId'] . '  จาก sneaksdeal.com ');
                $message->from('info@sneaksdeal.com', 'sndeaksdeal.com');
            });
            Orders::query()->where('id', $order->id)->update(['mail_status' => 'yes']);
            // } catch (Exception $e) {
            //     echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            // }

            $id = $request->order_id;
            $data = array(
                'id' => $request->order_id
            );
            $vouchers = array(
                'vouchers' => $vouchers
            );
            Session::forget('orders_id');
            Session::forget('discount_id');
            Cart::destroy();

            return view('pages.cart-success', $data, $vouchers);
        } else if ($request->payment_status == "001") {
            $orders = DB::table('tb_order')->where('id', '=', $request->order_id)->first();
            if ($orders->mail_status == null) {
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
                $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้สั่งซื้อสินค้า ตามรหัสการสั่งซื้อ เลขที่ $request->order_id <br>ตรวจรายละเอียดการสั่งซื้อโดยการคลิกลิงก์ หรือปุ่มด้านล่าง</div>";
                $message .= "			    <div style=\"font-size: 16px; text-align: center; font-weight: 500; margin-bottom: 10px;color:red;\">**จะทำการส่งรหัส Voucher หลังจากการชำระเงิน เรียบร้อยแล้ว</div>";
                $message .= "			    <div style=\"font-size: 20px; text-align: center; margin: 30px 0;\">";
                $message .= "			        <a style=\"display:inline-block;color:black;\" href=\"https://www.sneaksdeal.com/order-detail/$request->comfirm_payment\">คลิกที่นี่</a>";
                $message .= '			    </div>';
                $message .= "			    <div style=\"display:block; text-align:center; margin-top:15px; margin-bottom:15px;\">
                                        <a href=\"tel:+6667701732\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                                        <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon02.png\">
                                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">091-718-4432</span>
                                    </a>
                                    <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                                        <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
                                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\"> Sneak Out หนีเที่ยว</span>
                                    </a>
                                    <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
                                        <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
                                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">info.sneaksdeal@gmail.com</span>
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
                // try {
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
                $mail->FromName = "https://sneaksdeal.com";
                $mail->Subject = "กรุณายืนยันการสั่งซื้อ Voucher   ของ https://sneaksdeal.com ";
                $mail->Body = $message;
                $mail->AddAddress(Session::get('email'));
                $mail->set('X-Priority', '3');
                if ($request->order_id != "") {
                    $mail->Send();
                }
            }


            // } catch (Exception $e) {
            //     echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            // }
            $orders2 = Orders::find($request->order_id);
            $orders2->mail_status = 1;
            $orders2->save();

            $id = $request->order_id;
            $data = array(
                'id' => $request->order_id
            );
            Session::forget('orders_id');
            Session::forget('discount_id');
            Cart::destroy();
            // return redirect('pages.cart-success', compact($id));
            return view('pages.cart-success', $data);
        } else {
            $orders = DB::table('order_vouchers')->where('orders_id', '=', $request->order_id)->get();


            foreach ($orders as $order) {

                $voucher = Voucher::find($order->voucher_id);


                $voucher2 = DB::table('tb_voucher')->where('voucher_id', '=', $order->voucher_id)->update([
                    'qty_voucher' => $voucher->qty_voucher + 1,
                ]);
                $orders2 = DB::table('tb_order')->where('id', '=', $request->order_id)->first();
                if ($orders2->discount_id != null) {
                    $discount = DB::table('tb_discount')->where('discount_id', '=', $orders2->discount_id)->first();
                    DB::table('tb_discount')->where('discount_id', '=', $orders2->discount_id)->update([
                        'discount_qty' => $discount->discount_qty + 1,
                    ]);
                }
            }
            Session::forget('orders_id');
            Session::forget('discount_id');
            Cart::destroy();
            return redirect(url('/cart'))->with('danger', 'ท่านได้ทำการยกเลิก การสั่งซื้อ Voucher');
        }

    }


    public function testmail($id)
    {

        function DateThai($strDate)
        {
            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("j", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
        }

        $order = DB::table('tb_order')
            ->leftjoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->leftjoin('tb_member', 'tb_order.user_id', '=', 'tb_member.id_member')
            ->where('id', '=', $id)
            ->first();


        $vouchers = DB::table('tb_voucher')
            ->leftjoin('tb_order_detail', 'tb_voucher.voucher_id', '=', 'tb_order_detail.voucher_id')
            ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->where('tb_order.id', '=', $id)->get();

        $order_vouchers = DB::table('order_vouchers')
            ->leftjoin('tb_voucher', 'order_vouchers.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->where('orders_id', '=', $id)->get();

        $message = '<html>';
        $message .= '<head>';
        $message .= '<link href="https://fonts.googleapis.com/css?family=Prompt:400,500,700|Roboto:400,500,700" rel="stylesheet">';
        $message .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css" />';

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

        $message .= "	<div class=\"page_container\" style=\"padding: 15px 40px; max-width: 800px;min-width: 800px; width: 100%; background: #fbdc07; font-family: 'Roboto', 'Prompt', sans-serif;
                    color: #000;\">";
        $message .= "			    <img class=\"logo\" src=\"https://www.sneaksdeal.com/img/sneakoutlogo.jpg\"  style=\"margin: 0 auto 25px auto; width: 120px; height: auto; display: block;\">";
        $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้สั่งซื้อสินค้า ตามรหัสการสั่งซื้อ เลขที่ $order->id </div>";
        $message .= "
                                            <div class=\"bg_white breaknewpage\" style=\"     background-color: #FFF;
                                        padding: 15px;
                                        max-width: 800px;
                                        min-width: 800px;
                                        margin-top: 10px;\">
                                                <div class=\"head_order\" style=\"font-size: 18px;
                                        font-weight: 500;
                                       
                                        width: 48%;
                                        display: inline-block;
                                        vertical-align: top; \">
                                                   
    
                                                </div>
                                        <div class=\"time_order\" style=\" 
                                        max-width: 740px;
                                        min-width: 740px;
                                        display: inline-block;
                                        font-size: 13px;
                                        text-align: right;
                                        vertical-align: top;\">
                                                    Date " . DateThai($order->time_created, 'd/m/Y') . "
                                                </div>
                                                <div style=\"width:50%; text-align: left; font-size:18px; font-weight: 500; \"> 
    
                                                <table class=\"table_order table-responsive\" style=\"    font-size: 15px;
                                        width: 100%;
                                        max-width: 740px;
                                        min-width: 740px;
                                        margin-top: 15px;
                                        border-collapse: collapse; \">
                                                    <tr class=\"bg_gd\" style=\"     background: #325b87;\">
                                                        <th style=\"    font-weight: 400;
                                        text-align: center;
                                        color: #FFF;
                                        padding-bottom: 5px;
                                        padding-top: 5px; \" colspan=\"3\">สินค้า<span style=\"font-size: 11px;
                                        display: block; \">Voucher Description</span></th>
                                                        <th style=\"    font-weight: 400;
                                        text-align: center;
                                        color: #FFF;
                                        padding-bottom: 5px;
                                        padding-top: 5px; \">หน่วย<span style=\"font-size: 11px;
                                        display: block; \">Unit</span></th>
                                                        <th style=\"    font-weight: 400;
                                        text-align: center;
                                        color: #FFF;
                                        padding-bottom: 5px;
                                        padding-top: 5px; \">ราคาต่อหน่วย<span style=\"font-size: 11px;
                                        display: block; \">Price/Unit</span></th>
                                                      
                                                    </tr>";

        $sumtotal = 0;
        foreach ($vouchers as $key => $voucher) {
            $total = $voucher->priceper * $voucher->qty;
            $sumtotal += $total;
            $key += 1;
            $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
                                                                                <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" colspan=\"3\">
                                                                                    {$voucher->name_voucher}.
                                                                                </td>
                                                                                <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;
                                                    font-size: 13px;     text-align: center; \">
                                                                                    {$voucher->qty}
                                                                                </td>
                                                                                <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;
                                                    font-size: 13px;     text-align: center; \">
                                                                                    {$voucher->priceper}
                                                                                </td>
                                                                              
                                                                            </tr>";
        }
        if ($order->discount_main != 0) {
            $total = $sumtotal - $order->discount_bath;
            $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
                                                    <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" colspan=\"3\">
                                                        
                                                    </td>
                                                    <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px; font-size: 13px;     text-align: center; \">
                                                                                    รหัสพิเศษ
                                                    </td>
                                                                                <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;
                                                    font-size: 13px;     text-align: center; \">
                                                                                    {$order->discount_code}
                                                                                </td>
                                                                              
                                                    </tr>";

            $message .= "<tr class=\"borderdot\" style=\" \">
                                                                        <td style=\"    padding-top: 15px;
                                                        border-bottom: 1px solid #eee; \" colspan=\"8\"></td>
                                                                    </tr>
                                                                    <tr style=\" \">
                                                                        <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\"></td>
        
                                                                        <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;    text-align: right;\" class=\"num_total\"></td>
                                                                    </tr>
                                                                </table>
                                                            </div>";
        } else {

            $message .= "<tr class=\"borderdot\" style=\" \">
                                                    <td style=\"    padding-top: 15px;
                                                    border-bottom: 1px solid #eee; \" colspan=\"8\"></td>
                                                                </tr>
                                                                <tr style=\" \">
                                                                    <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\"></td>
    
                                                                    <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;    text-align: right;\" class=\"num_total\"></td>
                                                                </tr>
                                                            </table>
                                                        </div>";
        }

        $message .= '			    </div>';
        $message .= "
                                            <div class=\"bg_white breaknewpage\" style=\"     background-color: #FFF;
                                            max-width: 800px;
                                            min-width: 800px;
                                        padding: 15px;
                                        margin-top: 10px;\">
                                               
                                                <div style=\"width:50%; text-align: left; font-size:18px; font-weight: 500; \"> 
                                                    รายละเอียด Voucher ตามรายการสั่งซื้อ
                                                <table class=\"table_order\" style=\"    font-size: 15px; width: 740px;max-width: 740px;min-width: 740px;margin-top: 15px;border-collapse: collapse; \" border=\"1\">
                                                    <tr class=\"bg_gd\">
                                                        <th style=\"    font-weight: 400;text-align: center;color: black;padding-bottom: 5px;padding-top: 5px; \" >#</th>
                                                        <th style=\"    font-weight: 400;text-align: center;color: black;padding-bottom: 5px;padding-top: 5px; \">ชื่อ</th>
                                                        <th style=\"    font-weight: 400;text-align: center;color: black;padding-bottom: 5px;padding-top: 5px; \">รหัส</th>
                                                        <th style=\"    font-weight: 400;text-align: center; color: black;padding-bottom: 5px;padding-top: 5px; \">รหัสยืนยัน</th>
                                                    </tr>";
        foreach ($order_vouchers as $key => $order_voucher) {
            $key += 1;
            $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
                                                        <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" >{$key}</td>
                                                        <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \"> $order_voucher->name_main  :  $order_voucher->name_voucher</td>
                                                        <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">{$order_voucher->code_voucher}</td>
                                                        <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">{$order_voucher->code_confirm}</td>
                                                    </tr>";
        }
        $message .= "
                                                </table>
                                                </div>";
        $message .= '			    </div>';
        $message .= "			    <div style=\"display:block; text-align:center; margin-top:15px; margin-bottom:15px;\">
                                        <a href=\"tel:+6667701732\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                                        <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon02.png\">
                                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">091-718-4432</span>
                                    </a>
                                    <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                                        <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
                                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\"> Sneak Out หนีเที่ยว </span>
                                    </a>
                                    <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
                                        <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
                                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">info.sneaksdeal@gmail.com</span>
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


        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->IsHTML(true);
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->CharSet = "utf-8";
        $mail->SetFrom($order->email, $order->name_member . ' ' . $order->lastname_member);
        $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
        $mail->Password = 'sneaksdeal1234';       //Pass Sent
        $mail->From = 'sneaksdeal2018@gmail.com';    //User
        $mail->FromName = "Sneaksdeal";
        $mail->Subject = "รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่  $order->id  จาก https://sneaksdeal.com";
        $mail->Body = $message;
        $mail->AddAddress($order->email);
        $mail->set('X-Priority', '3');


        if (!$mail->Send()) {

            echo 'ยังไม่สามารถส่งเมลล์ได้ในขณะนี้ ' . $mail->ErrorInfo;
            exit;
        } else {
            echo 'ส่งเมลล์สำเร็จ';
        }
    }


}
