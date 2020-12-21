<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Provinces;
use App\Amphures;
use App\Districts;
use App\Orders;
use App\Voucher;
use App\Discount;
use App\Order_details;
use Session;
use DB;
use App\Http\Requests\ChckEditAccount;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Hash;
use Redirect;

class AccountCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Session::get('email')) {
            return redirect('sign-in');
        }
        Voucher::query()->where('date_open', '<=', date('Y-m-d H:i:s'))->where('status_countdown', 'post')->update(['status_countdown' => 'sale']);
        $orders =  Orders::query()->whereIn('status_order',['001','002'])->whereRaw("created_at <= NOW( ) - INTERVAL 24 HOUR")->get();
        foreach ($orders as $order) {
            if ($order->order_discount > 0 && $order->refund_discount == 'no' && $order->discount_id != ''&& $order->discount_id != 0) {
                Discount::query()->where('discount_id', $order->discount_id)->update(['discount_qty' => DB::raw('discount_qty + 1')]);
                Orders::query()->where('id', $order->id)->update(['refund_discount' => 'yes', 'discount_id'=>0, 'discount_code_order'=> NULL]);
            }
            $getData  = Order_details::query()->where('order_id', $order->id)->where('refund_stock', 'no')->get();
            foreach ($getData as $r) {
                Voucher::query()->where('voucher_id', $r->voucher_id)->update(['qty_voucher' => DB::raw('qty_voucher +' . $r->qty)]);
                Order_details::query()->where('odt_id', $r->odt_id)->update(['refund_stock' => 'yes']);
            }
            Orders::query()->where('id', $order->id)->update(['status_order' => '999']);
        }
        $members = DB::table('tb_member')
            ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
            ->select('tb_member.*', 'provinces.name_th as p_name')
            ->where('id_member', '=', Session::get('id_member'))
            ->first();
        $order_details = DB::table('tb_order')
            ->leftJoin('tb_order_detail', 'tb_order_detail.order_id', '=', 'tb_order.id')
            ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
            ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
            ->select('tb_order.*', 'tb_order_detail.*', 'tb_voucher.*', 'tb_order_detail.created_at as od_create', 'tb_discount.*', DB::raw('SUM(tb_order_detail.total) as sum_amount'), DB::raw('SUM(tb_voucher.price_agent*tb_order_detail.qty) as sum_amount_agent'))
            ->where('user_id', '=', Session::get('id_member'))
            ->orderby('tb_order.id', 'desc')
            ->groupBy('order_id')
            ->get();

        return view('pages.customeraccount', compact('members', 'order_details'));
    }

    public function howitworkCheck(Request $request){
        $checkHowToItWork = $request->checkHowToItWork;
        $idMember = Session::get('id_member');
        Member::query()->where('id_member',$idMember)->update(['is_activate_hoitwork'=>'yes']); 
        session(['is_activate_hoitwork' => 'yes']);
        Session::save();
      return redirect($checkHowToItWork);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, $id)
    {
        $members = DB::table('tb_member')
            ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
            ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
            ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
            ->select('tb_member.*', 'districts.name_th as d_name', 'amphures.name_th as a_name', 'provinces.name_th as p_name')
            ->where('id_member', '=', Session::get('id_member'))
            ->first();

        $amphures = DB::table('amphures')
            ->where('id', '=', $members->amphures_id)
            ->get();
        $districts = DB::table('districts')
            ->where('id', '=', $members->districts_id)
            ->get();

        $postcode = DB::table('districts')
            ->select('zip_code')
            ->where('id', '=', $members->districts_id)
            ->first();
        $provinces = Provinces::all();

        $status = @$request->status;
        $data = array(
            'provinces' => $provinces,
            'amphures' => $amphures,
            'districts' => $districts,
            'postcode' => $postcode,
            'members' => $members,
            'status' => $status
        );

        return view('pages.customeraccountedit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = (@$request->status == '' ? 'profile' : $request->status);
        if ($status == 'profile') {
            if (@$request->current_password != "" || @$request->current_password != null) {
                $checkmember = Member::find($id);
                if (Hash::check($request->current_password, $checkmember->password)) {
                    $rules = [
                        'new_password' => 'required|min:8|regex:/^[a-zA-Z0-9\s]+$/',
                        'confirm_new_password' => 'required|min:8|regex:/^[a-zA-Z0-9\s]+$/|same:new_password',
                    ];

                    $customMessages = [
                        'new_password.required' => 'กรุณาใส่ข้อมูล รหัสผ่านใหม่',
                        'confirm_new_password.required' => 'กรุณาใส่ข้อมูล ยืนยันรหัสผ่านใหม่',
                    ];

                    $this->validate($request, $rules, $customMessages);

                    $members = Member::find($id);
                    $members->name_member = $request->firstName;
                    $members->lastname_member = $request->lastName;
                    $members->tel_member = $request->tel;
                    $members->email = $request->email;
                    $members->password = Hash::make($request->new_password);
//                $members->address_member = $request->address;
//                $members->provinces_id = $request->province;
//                $members->amphures_id = $request->ampuhers;
//                $members->districts_id = $request->districts;
//                $members->zip_code = $request->postcode;
                    $members->save();

                    $members = DB::table('tb_member')
                        ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
                        ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
                        ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
                        ->select('tb_member.*', 'districts.name_th as d_name', 'amphures.name_th as a_name', 'provinces.name_th as p_name')
                        ->where('id_member', '=', Session::get('id_member'))
                        ->first();
                    $orders = DB::table('tb_order')
                        ->where('user_id', '=', Session::get('id_member'))
                        ->first();
                    if ($orders != null) {
                        $order_details = DB::table('tb_order_detail')
                            ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                            ->select('tb_order_detail.*', 'tb_voucher.*', 'tb_order_detail.created_at as od_create')
                            ->where('order_id', '=', $orders->id)
                            ->get();

                        $data = array(
                            'members' => $members,
                            'order_details' => $order_details,
                        );
                        return redirect(route('customeraccount.index'))->with('success', 'แก้ไขข้อมูลส่วนตัวผู้ใช้งานเรียบร้อย!');
                    } else {
                        $order_details = null;
                        $data = array(
                            'members' => $members,
                            'order_details' => $order_details,
                        );
                        return redirect(route('customeraccount.index'))->with('success', 'แก้ไขข้อมูลส่วนตัวผู้ใช้งานเรียบร้อย!');
                    }
                } else {
                    return Redirect::back()->with('danger', 'กรุณารหัสผ่านเดิม ให้ถูกต้อง!');
                }
            } else {
                $members = Member::find($id);
                $members->name_member = $request->firstName;
                $members->lastname_member = $request->lastName;
                $members->tel_member = $request->tel;
                $members->email = $request->email;
//                $members->add ress_member = $request->address;
//                $members->provinces_id = $request->province;
//                $members->amphures_id = $request->ampuhers;
//                $members->districts_id = $request->districts;
//                $members->zip_code = $request->postcode;
                $members->save();

                $members = DB::table('tb_member')
                    ->leftJoin('districts', 'tb_member.districts_id', '=', 'districts.id')
                    ->leftJoin('amphures', 'tb_member.amphures_id', '=', 'amphures.id')
                    ->leftJoin('provinces', 'tb_member.provinces_id', '=', 'provinces.id')
                    ->select('tb_member.*', 'districts.name_th as d_name', 'amphures.name_th as a_name', 'provinces.name_th as p_name')
                    ->where('id_member', '=', Session::get('id_member'))
                    ->first();
                $orders = DB::table('tb_order')
                    ->where('user_id', '=', Session::get('id_member'))
                    ->first();
                if ($orders != null) {
                    $order_details = DB::table('tb_order_detail')
                        ->leftJoin('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                        ->select('tb_order_detail.*', 'tb_voucher.*', 'tb_order_detail.created_at as od_create')
                        ->where('order_id', '=', $orders->id)
                        ->get();

                    $data = array(
                        'members' => $members,
                        'order_details' => $order_details,
                    );
                    return redirect(route('customeraccount.index'))->with('success', 'แก้ไขข้อมูลส่วนตัวผู้ใช้งานเรียบร้อย!');
                } else {
                    $order_details = null;
                    $data = array(
                        'members' => $members,
                        'order_details' => $order_details,
                    );
                    return redirect(route('customeraccount.index'))->with('success', 'แก้ไขข้อมูลส่วนตัวผู้ใช้งานเรียบร้อย!');
                }
            }
        } else {
            $members = Member::find($id);
//            $members->name_member = $request->firstName;
//            $members->lastname_member = $request->lastName;
//            $members->tel_member = $request->tel;
//            $members->email = $request->email;
            $members->email_send_order = $request->email_send_order;
            $members->address_member = $request->address;
            $members->provinces_id = $request->province;
            $members->amphures_id = $request->ampuhers;
            $members->districts_id = $request->districts;
            $members->zip_code = $request->postcode;
            $members->save();
            return redirect(route('customeraccount.index'))->with('success', 'แก้ไชช้อมูลที่อยู่เรียบร้อย!');

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function cancel($id){
        $orders =  Orders::query()->where('id', $id)->get();
        foreach ($orders as $order) {
            Orders::query()->where('id',$order->id)->update(['status_order'=>'999']);
            if ($order->order_discount > 0 && $order->refund_discount == 'no' && $order->discount_id != '' && $order->discount_id != 0) {
                Discount::query()->where('discount_id', $order->discount_id)->update(['discount_qty' => DB::raw('discount_qty + 1')]);
                Orders::query()->where('id', $order->id)->update(['refund_discount' => 'yes', 'discount_id' => 0, 'discount_code_order' => NULL]);
            }
            $getData  = Order_details::query()->where('order_id', $order->id)->where('refund_stock', 'no')->get();
            foreach ($getData as $r) {
                Voucher::query()->where('voucher_id', $r->voucher_id)->update(['qty_voucher' => DB::raw('qty_voucher +' . $r->qty)]);
                Order_details::query()->where('odt_id', $r->odt_id)->update(['refund_stock' => 'yes']);
            }
        }
        return redirect('customeraccount');
    }

    public function showdetail(Request $request, $id)
    {
        $idOrder = @$request->runId;
        $payment = @$request->payment;
        $orders = DB::table('tb_order');
        if ($idOrder != '') {
            $orders = $orders->where('id', $idOrder);
        } else {
            $orders = $orders->where('comfirm_payment', $id);
        }
        $orders = $orders->first();
        $order_details = DB::table('tb_order')
            ->leftJoin('tb_order_detail', 'tb_order_detail.order_id', '=', 'tb_order.id');
        if ($idOrder != '') {
            $order_details = $order_details->where('tb_order.id', $idOrder);
        } else {
            $order_details = $order_details->where('tb_order.comfirm_payment', '=', $id);
        }
        $order_details = $order_details->orderby('tb_order.id', 'desc')->get();
        $status_order = @$orders->status_order;
        if ($status_order == "000") {
            $status_order2 = "<span class='text-success'>ชำระเงินแล้ว</span>";
        } else if ($status_order == "001") {
            $status_order2 = "<span class='text-warning'>กำลังดำเนินการ</span>";
        } else if ($status_order == "002") {
            $status_order2 = "<span class='text-warning'>ยกเลิกการชำระ</span>";
        } else {
            $status_order2 = "<span class='text-danger'>ชำระเงินล้มเหลว/หมดเวลาชำระเงิน</span>";
        }
        $getAddressOrder = \App\LogAddressOrder::query()->where('ref_order_id', $orders->id)->first();
        $value = json_decode($getAddressOrder->json_address);
        $getProvince = DB::table('provinces')->where('id', $value->provinces_id)->first();
        $p_name = $getProvince->name_th;
        if ($value->provinces_id == 10) {
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


        $address = $value->address_member . " " . $d . $value->districts_id . " " . $a . $value->amphures_id . " จ." . $p_name . " " . $value->zip_code;
        $name = $value->name_member.' '.$value->lastname_member;
        // dd($order_details);
        $data = array(
            'order_details' => $order_details,
            'orders' => $orders,
            'status_order' => $status_order2,
            'payment' => $payment,
            'address' => $address,
            'name' => $name,
        );
        return view('pages.order-detail', $data);
    }

    public function forget(Request $request)
    {
        $member = DB::table('tb_member')->where('email', '=', $request->email)->first();
        if ($member != null) {
            if ($member->facebook_id != null || $member->google_id != null) {
                return redirect(url('forgotpassword'))->with('danger', 'ไม่สามารถเปลี่ยน รหัสผ่านได้ โปรดติดต่อเจ้าหน้าที่!');

            } else {
                $letter = chr(rand(65, 90));
                $letter2 = chr(rand(65, 90));
                $seed = str_split('0123456789'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $newpassword = $letter . $letter2 . time();
                DB::table('tb_member')
                    ->where('email', $request->email)
                    ->update(['password' => bcrypt($newpassword)]);

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
                $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ทางเว็บไซต์ ได้ทำงานการเปลี่ยนรหัสผ่านของท่าน : $newpassword</div>";
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

                // echo $message;
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
                    $mail->SetFrom($request->email, $request->firstName . ' ' . $request->lastName);
                    $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
                    $mail->Password = 'sneaksdeal1234';       //Pass Sent
                    $mail->From = 'sneaksdeal2018@gmail.com';    //User
                    $mail->FromName = "https://sneaksdeal.com";
                    $mail->Subject = "เปลี่ยนรหัสผ่าน การเข้าใช้งานระบบ  https://sneaksdeal.com ";
                    $mail->Body = $message;
                    $mail->AddAddress($request->email);
                    $mail->set('X-Priority', '3');
                    $mail->Send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }

                return redirect(url('/forgotpassword'))->with('success', 'ส่งข้อมูลรหัสผ่านใหม่ไปยัง อีเมล์ของท่านเรียบร้อยแล้ว!!');
            }
        } else {
            return redirect(url('forgotpassword'))->with('danger', 'โปรดตรวจสอบ อีเมล์ของท่านอีกครั้ง!');
        }
    }
}