<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Orders;
use App\Order_details;
use App\Member;
use App\Voucher;
use App\LogAddressOrder;
use Session;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Model\admin\SelectVoucherModel;


class TestController extends Controller
{

    public function test()
    {
        // $fetchMember = Member::query()->find(Session::get('id_member'));
        // $createLogAddress = new LogAddressOrder();
        // $createLogAddress->ref_order_id = 1;
        // $createLogAddress->json_address = $fetchMember;
        // $createLogAddress->save();
    }

    public function tester()
    {
        $tb_orders = DB::table('tb_order')->where('status_order','=',000)->get();

        foreach($tb_orders as $tb_order){
          $order =   Orders::find($tb_order->id);
          echo  $order->id."<br/>";
        }

    }
    public  function DateThai($strDate)
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
    public function testConfirmOrder(){
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
            ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
            ->where('tb_order.id','=',$order_id)->get();
        $order_vouchers = DB::table('order_vouchers')
            ->leftjoin('tb_voucher','order_vouchers.voucher_id','=','tb_voucher.voucher_id')
            ->where('orders_id','=',$order_id)->get();
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
                                                    <th style=\"    font-weight: 400;
                                    text-align: center;
                                    color: #FFF;
                                    padding-bottom: 5px;
                                    padding-top: 5px; \" colspan=\"2\">รวม<span style=\"font-size: 11px;
                                    display: block; \">Total</span></th>
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
                                                                            <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;   padding-top: 5px;
                                                font-size: 13px; width:50px; text-align: right;\" >
                                                                                {$total} บาท
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
                                                                            <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;   padding-top: 5px;
                                                font-size: 13px; width:50px; text-align: right;\" >
                                                                                -{$order->discount_bath} บาท
                                                                            </td>
                                                </tr>";

            $message .= "<tr class=\"borderdot\" style=\" \">
                                                                    <td style=\"    padding-top: 15px;
                                                    border-bottom: 1px solid #eee; \" colspan=\"8\"></td>
                                                                </tr>
                                                                <tr style=\" \">
                                                                    <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; font-size: 11px;\" colspan=\"4\" class=\"text_vat\">ราคาสินค้า รวมภาษีมูลค่าเพิ่มแล้ว 7% ( VAT Included 7% ) </td>
    
                                                                    <td style=\" font-family: 'Roboto', 'Prompt', sans-serif;    text-align: right;\" class=\"num_total\"></td>
                                                                    <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; text-align: right;\"> $total บาท </td>
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
                                                                <td style=\"font-family: 'Roboto', 'Prompt', sans-serif; text-align: right;\">$sumtotal บาท </td>
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
                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">08-677-01732</span>
                        </a>
                        <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                            <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
                            <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">Sneaksdeal</span>
                        </a>
                        <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
                            <img style=\"display:block; margin:0 auto 10px auto;width:26px;height:26px\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
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
            $data = array(
                'id' => $order_id
            );
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->IsHTML(true);
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
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
        if(count($vouchers) > 0){
            $mail->Send();
        }
//            Session::forget('discout_promo_bath');
//            Session::forget('discout_promo_per');
//            Session::forget('discount_code');
//            Session::forget('orders_id');
//            Session::forget('discount_id');
//            Cart::destroy();
            // return redirect(url('pages.cart-success'), compact($id));
            return view('pages.cart-success',$data);

    }

    public function email(){
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
            ->leftjoin('tb_order','tb_order_detail.order_id','=','tb_order.id')
            ->where('tb_order.id','=',$order_id)->get();
        $order_vouchers = DB::table('order_vouchers')
            ->leftjoin('tb_voucher','order_vouchers.voucher_id','=','tb_voucher.voucher_id')
            ->where('orders_id','=',$order_id)->get();
//        $mail->From  = 'sneaksdeal2018@gmail.com';    //User
//        $mail->FromName = "Sneaksdeal";
//        $mail->Subject  = "รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่  $order->id  จาก https://sneaksdeal.com";
        $data = array('name' => "aof", 'email' => 'dev.webapp2019@gmail.com');
       $check = Mail::send(['html' => 'email-voucher'], $data, function ($message) use ($data) {
            $message->to($data['email'], 'name user')
                ->subject('รายละเอียดการสั่งซื้อ Voucher ตามรหัสสั่งซื้อ เลขที่ 00000003410 จาก https://sneaksdeal.com');
            $message->from('sneaksdeal2018@gmail.com', 'https://sneaksdeal.com');
        });
       if(@$check){
           dd(true);
       }else{
           dd(false);
       }
        return view('email-voucher');
    }

}
