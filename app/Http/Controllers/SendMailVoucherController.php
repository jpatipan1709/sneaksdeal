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

class SendMailVoucherController extends Controller
{
    public function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strHour= date("H",strtotime($strDate));
        $strMinute= date("i",strtotime($strDate));
        $strSeconds= date("s",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    }

    public function testMail(){
        $order_id = '00000003410';
        $payment_status = '000';
        $member = DB::table('tb_member')
            ->leftjoin('tb_order','tb_member.id_member','=','tb_order.user_id')
            ->where('tb_order.id','=',$order_id)
            ->first();
        $token = str_random(25);
        Orders::where('id', $order_id)
            ->update([
                'status_order' => $payment_status,
                'comfirm_payment' => $token
            ]);
        $order = DB::table('tb_order')
            ->leftjoin('tb_discount','tb_order.discount_id','=','tb_discount.discount_id')
            ->where('id','=',$order_id)
            ->first();
        $vouchers = DB::table('tb_voucher')
            ->leftjoin('tb_order_detail','tb_voucher.voucher_id','=','tb_order_detail.voucher_id')
            ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
            ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
            ->where('tb_order.id','=',$order_id)->get();
        $order_vouchers = DB::table('order_vouchers')
            ->leftjoin('tb_voucher','order_vouchers.voucher_id','=','tb_voucher.voucher_id')
            ->leftjoin('main_voucher','tb_voucher.relation_mainid','=','main_voucher.id_main')
            ->where('orders_id','=',$order_id)->get();
        return view('test-email-ajax',compact('member','order_id','order','order_vouchers','vouchers'));
    }
    public function requestSendmailCartSuccess(Request $request)
    {
        $order = json_decode($request->order);
        $vouchers = json_decode($request->vouchers);
        $order_vouchers = json_decode($request->order_vouchers);
        $member =json_decode($request->member);
        $order_id = $request->order_id;
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
        $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้สั่งซื้อสินค้า ตามรหัสการสั่งซื้อ เลขที่ $order_id </div>";
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
                                                Date " . $this->DateThai($order->created_at, 'd/m/Y') . "
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
                                                                            $voucher->name_main  : $voucher->name_voucher
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
        if($order->discount_main != 0){
            $total = $sumtotal - $order->discount_bath;
            $message .= "<tr class=\"wrap_product\" style=\" padding-bottom: 25px; \">
                                                <td style=\" font-family: 'Roboto', 'Prompt', sans-serif; \" colspan=\"3\">

                                                </td>
                                                <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px; font-size: 13px;     text-align: center; \">
                                                                                รหัสส่วนลด
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
                                                                    <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\">ราคาสินค้า รวมภาษีมูลค่าเพิ่มแล้ว 7% ( VAT Included 7% ) </td>

                                                                    <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;    text-align: right;\" class=\"num_total\"></td>
                                                                </tr>
                                                            </table>
                                                        </div>";
        }else{

            $message .= "<tr class=\"borderdot\" style=\" \">
                                                <td style=\"    padding-top: 15px;
                                                border-bottom: 1px solid #eee; \" colspan=\"8\"></td>
                                                            </tr>
                                                            <tr style=\" \">
                                                                <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\">ราคาสินค้า รวมภาษีมูลค่าเพิ่มแล้ว 7% ( VAT Included 7% ) </td>

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
                                                    <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">{$order_voucher->name_voucher}</td>
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
        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">Sneak Out หนีเที่ยว</span>
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
        $mail->SMTPDebug  = 1;
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPSecure = "tls";
        $mail->CharSet = "utf-8";
        $mail->SetFrom($member->email, $member->name_member.' '.$member->lastname_member);
        $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
        $mail->Password = 'sneaksdeal1234';       //Pass Sent
        $mail->From  = 'sneaksdeal2018@gmail.com';    //User
        $mail->FromName = "https://sneaksdeal.com";
        $mail->Subject  = "รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่  $order_id  จาก https://sneaksdeal.com ";
        $mail->Body  = $message;
        $mail->AddAddress($member->email);
        $mail->set('X-Priority', '3');
        // dd(count($vouchers));
      if($mail->Send()){
          return 'true';
      }else{
          return 'false';

      }

    }
}