<?php

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
$message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้สั่งซื้อสินค้า ตามรหัสการสั่งซื้อ เลขที่ ".$orderId." </div>";
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
                                                Date " . DateThai($order->time_created) . "
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
                                                    <td style=\"  font-family: 'Roboto', 'Prompt', sans-serif;  padding-top: 5px;font-size: 13px;     text-align: center; \">$order_voucher->name_voucher</td>
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
echo $message;