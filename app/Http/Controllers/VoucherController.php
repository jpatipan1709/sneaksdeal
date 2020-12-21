<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Voucher;
use App\Provinces;
use App\Amphures;
use App\Districts;
use App\Orders;
use App\Order_details;
use App\Member;
use App\Discount;
use App\Model\DiscountVoucher;
use App\Model\admin\MainVoucherModel;
use App\Model\admin\VoucherModel;
use App\Order_voucher;
use App\LogAddressOrder;
use App\ClickVoucherAddCart;
use Cart;
use DB;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Requests\ChkAfterOrder;
use Redirect;

class VoucherController extends Controller
{


    public function getAddToCart(Request $request, $id)
    {
        $today = Date('Y-m-d H:i:s');
        $voucher = Voucher::find($id);
        $orders = Orders::leftjoin('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
            ->where('voucher_id', '=', $id)
            ->where('status_order', '=', '000')
            ->orWhere('status_order', '=', '002')
            ->count();
        $orders_count = Order_voucher::leftjoin('tb_order', 'order_vouchers.orders_id', '=', 'tb_order.id')
            ->where('voucher_id', '=', $id)
            ->where(function ($query) {
                $query->where('status_order', '=', '000')
                    ->orWhere('status_order', '=', '002');
            })
            // ->where('status_order','=','000')
            // ->orWhere('status_order','=','002')
            ->count();
        // dd($orders); 
//        if( $orders_count > $voucher->qty_voucher ){
        if ($voucher->qty_voucher <= 0) {
            return Redirect::back()->with('danger', 'ขออภัย Voucher นี้ ได้ขายหมดแล้ว..');
        } elseif ($voucher->date_close < $today) {
            return Redirect::back()->with('danger', 'ขออภัย Voucher นี้ หมดเวลาในการขายแล้ว...');
        } else {
            $getMember =  Member::query()->find(@Session::get('id_member'));
            $createClick = new ClickVoucherAddCart();
            $createClick->ref_id_voucher = $voucher->voucher_id;
            $createClick->full_name = @$getMember->name_member.' '.@$getMember->lastname_member;
            $createClick->email = @$getMember->email;
            $createClick->tel = @$getMember->tel_member;
            $createClick->type_user = (@Session::get('id_member') == ''?'guest': 'member');
            $createClick->json_data = $voucher;
            $createClick->get_ip = $request->ip();
            $createClick->save();
            Cart::add($voucher->voucher_id, $voucher->name_voucher, 1, $voucher->price_sale, ['main_id' => $voucher->relation_mainid]);
            $cartitems = Cart::content();
            return redirect('/cart');
        }

        // return view(url('/cart'));
    }


    public function deleteCart(Request $request)
    {
        $rowID = $request->rowID;
        Cart::remove($rowID);
        return 1;
    }

    public function updateCart(Request $request)
    {

        $rowID = $request->rowID;
        $newQty = $request->newQty;

        Cart::update($rowID, $newQty);
        $total = Cart::total();
        $count = Cart::count();
        $cartItem = Cart::get($rowID);


        return response()->json([
            'count' => $count,
            'subtotal' => $cartItem->subtotal,
            'qty' => $cartItem->qty,
            'id' => $cartItem->id,
            'total' => $total
        ]);
    }

    public function destroyCart()
    {
        Cart::destroy();
        $cartitems = Cart::content();
        return view('pages.cart', compact('cartitems'));
    }

    public function getCart()
    {
        $cartitems = Cart::content();
        if ($cartitems == null) {
            return view('pages.cart', compact('cartitems'));
        }


        return view('pages.cart', compact('cartitems'));
    }

    public function CartSummary()
    {
        return view('pages.cart-summary');
    }

    public function getPaymentStarting()
    {
        if(@Session::get('id_member') == ''){
            return redirect()->Back();
        }
        Session::forget('total_end');
        $members = DB::table('tb_member')
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

        $cartitems = Cart::content();
        $provinces = Provinces::all();
        $data = array(
            'cartitems' => $cartitems,
            'provinces' => $provinces,
            'amphures' => $amphures,
            'districts' => $districts,
            'postcode' => $postcode,
            'members' => $members
        );

        return view('pages.paymentstarting', $data);

    }

    public function paymentending(Request $request){
        $cartitems = Cart::content();
        $total_after = Session::get('total_after');
        $total_discount = Session::get('total_discount');
        $total_end = Session::get('total_end');
        return view('pages.paymentgateway', compact('cartitems', 'total_end', 'total_discount', 'total_after'));    
    }

    public function getPaymentEnding(ChkAfterOrder $request)
    {
        $total_after = $request->total_after;
        $total_discount = $request->total_discount;
        $total_end = $request->total_end;
        
        session(['step_3' => 'true']);
        session(['total_after' => $request->total_after]);
        session(['total_discount' => $request->total_discount]);
        session(['total_end' => $request->total_end]);
        session(['email_voucher' => $request->email_voucher]);
        Session::save();
        Member::where('id_member', Session::get('id_member'))
            ->update([
                'name_member' => $request->firstName,
                'lastname_member' => $request->lastName,
                'tel_member' => $request->tel,
                'districts_id' => $request->districts,
                'email_send_order' => $request->email_voucher,
                'amphures_id' => $request->ampuhers,
                'provinces_id' => $request->province,
                'address_member' => $request->address,
                'zip_code' => $request->postcode,
            ]);

        $cartitems = Cart::content();
        return view('pages.paymentgateway', compact('cartitems', 'total_end', 'total_discount', 'total_after'));

    }

    public function getOrder(Request $request)
    {
        $cartitems = Cart::content();
        foreach ($cartitems as $KEY => $cartitem) {
            $voucher = Voucher::find($cartitem->id);
            if ($cartitem->qty > $voucher->qty_voucher) {
                return redirect(url('/cart'))->with('danger', 'จำนวน Voucher ของ ' . $cartitem->name . ' เกินจำนวนที่กำหนด');
            }
        }
        if (count(Cart::content()) != null || count(Cart::content()) > 0) {
            if (!empty(Session::get('discount_id'))) {
                $code = Session::get('discount_code');
                $discount = Discount::where('discount_id', Session::get('discount_id'))->first();
                if ($discount->type_discount == 'single_code') {
                    if ($discount->discount_qty > 0) {
                        Discount::where('discount_id', Session::get('discount_id'))
                            ->update([
                                'discount_qty' => ($discount->discount_qty) - 1
                            ]);
                    } else {
                        session([
                            'discout_promo_bath' => 0
                        ]);
                        Session::save();
                    }
                } else {
                    $subDiscount = DiscountVoucher::query()->where('discount_code_multiple', $code)->where('status_used', 'no')->first();
                    if ($subDiscount) {
                        DiscountVoucher::query()->where('discount_code_multiple', $code)->update(['status_used' => 'yes']);
                        Discount::where('discount_id', Session::get('discount_id'))
                            ->update([
                                'discount_qty' => ($discount->discount_qty) - 1
                            ]);
                    } else {
                        session([
                            'discout_promo_bath' => 0
                        ]);
                        Session::save();
                    }
                }
            } else {
                session([
                    'discout_promo_bath' => 0
                ]);
                Session::save();
            }
            $orders = new Orders;
            $orders->user_id = Session::get('id_member');
            $orders->status_order = '001';
            $orders->discount_id = @Session::get('discount_id');
            $orders->discount_code_order = @Session::get('discount_code');
            $orders->is_comfirm = 0;
            $orders->email_order = @Session::get('email_voucher');
            $orders->save();
            session(['orders_id' => $orders->id]);
            $fetchMember = Member::query()->find(Session::get('id_member'));
            $createLogAddress = new LogAddressOrder();
            $createLogAddress->ref_order_id = $orders->id;
            $createLogAddress->json_address = $fetchMember;
            $createLogAddress->save();
            if (@Session::get('discout_promo_bath') < 0) {
                $discout_promo_bath = 0;
            } else {
                $discout_promo_bath = (int)Session::get('discout_promo_bath');
            }
            $i = 0;
            $set_itmes = [];
            $check_add_discount = 0;
            foreach ($cartitems as $KEY => $cartitem) {
                $tb_voucher = DB::table('tb_voucher')
                    ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
                    ->where('voucher_id', '=', $cartitem->id)
                    ->first();

                $voucher = Voucher::find($cartitem->id);
                $voucher->qty_voucher = $tb_voucher->qty_voucher - $cartitem->qty;
                $voucher->save();
                $set_itmes['order_id'] = Session::get('orders_id');
                $set_itmes['voucher_id'] = $cartitem->id;
                $set_itmes['name_main'] = $tb_voucher->name_main;
                $set_itmes['main_id'] = $tb_voucher->id_main;
                $set_itmes['name_voucher'] = $tb_voucher->name_voucher;
                $set_itmes['qty'] = $cartitem->qty;
                $set_itmes['priceper'] = $cartitem->price;
                $set_itmes['price_agent'] = $tb_voucher->price_agent;
                $set_itmes['relation_facilityid'] = $tb_voucher->relation_facilityid;
                $set_itmes['qty_customer'] = $tb_voucher->qty_customer;
                $set_itmes['qty_night'] = $tb_voucher->qty_night;
                $set_itmes['term_voucher'] = $tb_voucher->term_voucher;
                $set_itmes['img_show'] = $tb_voucher->img_show;
                $set_itmes['total'] = ($cartitem->qty * $cartitem->price);
                $json = json_encode($set_itmes);
                $x = $cartitem->qty;
                if (Session::get('discount_id') != "" || Session::get('discount_id') != null) {
                    $tb_discount = DB::table('tb_discount')
                        ->where('discount_main', '=', $tb_voucher->id_main)
                        ->first();
                    $discount = Session::get('discount_id');
                    if ($tb_discount != null) {
                        $order_details = new Order_details;
                        $order_details->order_id = Session::get('orders_id');
                        $order_details->main_id = $tb_voucher->id_main;
                        $order_details->voucher_id = $cartitem->id;
                        $order_details->qty = $cartitem->qty;
                        if ($check_add_discount == 0) {
                            $order_details->discount = $discount;
                        } else {
                            $order_details->discount = null;
                        }
                        $order_details->priceper = $cartitem->price;
                        $order_details->total = ($cartitem->qty * $cartitem->price);
                        $order_details->data_detail = $json;
                        $order_details->save();

                        ++$check_add_discount;
                    } else {
                        $discount = null;
                        $order_details = new Order_details;
                        $order_details->order_id = Session::get('orders_id');
                        $order_details->main_id = $tb_voucher->id_main;
                        $order_details->voucher_id = $cartitem->id;
                        $order_details->qty = $cartitem->qty;
                        $order_details->discount = $discount;
                        $order_details->priceper = $cartitem->price;
                        $order_details->total = ($cartitem->qty * $cartitem->price);
                        $order_details->data_detail = $json;
                        $order_details->save();
                    }
                } else {
                    $discount = null;

                    $order_details = new Order_details;
                    $order_details->order_id = Session::get('orders_id');
                    $order_details->main_id = $tb_voucher->id_main;
                    $order_details->voucher_id = $cartitem->id;
                    $order_details->qty = $cartitem->qty;
                    $order_details->discount = $discount;
                    $order_details->priceper = $cartitem->price;
                    $order_details->total = ($cartitem->qty * $cartitem->price);
                    $order_details->data_detail = $json;
                    $order_details->save();
                }
                $set_itmes['odt_id'] = $order_details->id;
                $json2 = json_encode($set_itmes);

                DB::table('tb_order_detail')
                    ->where('odt_id', $order_details->id)
                    ->update(['data_detail' => $json2]);
                $odts = Order_details::where('order_id', Session::get('orders_id'))->get();
                for ($a = 0; $a < $x; $a++) {
                    $letter = chr(rand(65, 90));
                    $letter2 = chr(rand(65, 90));
                    $seed = str_split('0123456789'); // and any other characters
                    shuffle($seed); // probably optional since array_is randomized; this may be redundant
                    $rand = '';
                    foreach (array_rand($seed, 4) as $k) $rand .= $seed[$k];
                    $order_voucher = new Order_voucher;
                    $order_voucher->orders_id = Session::get('orders_id');
                    $order_voucher->order_detail_id = $odts[$i]->odt_id;
                    $order_voucher->voucher_id = $cartitem->id;
                    $order_voucher->code_voucher = $letter . $letter2 . $orders->id;
                    $order_voucher->stat_voucher = 'n';
                    $order_voucher->code_confirm = $rand;
                    $order_voucher->save();
                }
                $i++;
            }
            if (!empty($request->q14)) {
                Orders::where('id', Session::get('orders_id'))
                    ->update([
                        'status_payment' => $request->q14,
                        'status_order' => '001'
                        // 'status_order' => 999
                    ]);
            } else {
                Orders::where('id', Session::get('orders_id'))
                    ->update([
                        'status_payment' => $request->g5,
                        'status_order' => '001'
                        // 'status_order' => 999
                    ]);
            }
            $order = DB::table('tb_order')
                ->join('tb_order_detail', 'tb_order.id', '=', 'tb_order_detail.order_id')
                ->leftJoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                ->join('tb_voucher', 'tb_order_detail.voucher_id', '=', 'tb_voucher.voucher_id')
                ->select('tb_order.*', 'tb_order_detail.*', DB::raw("SUM(tb_order_detail.total) as sum_total"), 'tb_discount.*', 'tb_voucher.*')
                ->where('id', '=', Session::get('orders_id'))
                ->first();
            Orders::where('id', Session::get('orders_id'))
                ->update([
                    'order_discount' => (Session::get('discout_promo_bath')),
                    'order_total' => ($order->sum_total)
                ]);

            $checkPayment = $order->sum_total - Session::get('discout_promo_bath');
            if ($checkPayment > 0) {
                $payment_channel = @$request->payment_channel;
                // $payment_channel = '123';
// dd($payment_channel);
                Session::forget('discout_promo_bath');
                Session::forget('discout_promo_per');
                Session::forget('discount_code');
                Session::forget('orders_id');
                Session::forget('discount_id');
                Session::forget('total_after');
                Session::forget('total_discount');
                Session::forget('total_end');
                Session::forget('step_3');
                Cart::destroy();
                return view('pages.payment', compact('order', 'payment_channel'));
            } else {
                $payment_status = '000';
                $order_id = Session::get('orders_id');
                $member = DB::table('tb_member')
                    ->leftjoin('tb_order', 'tb_member.id_member', '=', 'tb_order.user_id')
                    ->where('tb_order.id', '=', $order_id)
                    ->first();

//                session(['email' => $member->email]);
//                session(['name_member' => $member->name_member]);
//                session(['lastname_member' => $member->lastname_member]);
//                session(['id_member' => $member->id_member]);


                $token = str_random(25);
                Orders::where('id', $order_id)
                    ->update([
                        'status_order' => $payment_status,
                        'comfirm_payment' => $token
                    ]);

                $order = DB::table('tb_order')
                    ->leftjoin('tb_discount', 'tb_order.discount_id', '=', 'tb_discount.discount_id')
                    ->where('id', '=', $order_id)
                    ->first();

                $vouchers = DB::table('tb_voucher')
                    ->leftjoin('tb_order_detail', 'tb_voucher.voucher_id', '=', 'tb_order_detail.voucher_id')
                    ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
                    ->where('tb_order.id', '=', $order_id)->get();

                $order_vouchers = DB::table('order_vouchers')
                    ->leftjoin('tb_voucher', 'order_vouchers.voucher_id', '=', 'tb_voucher.voucher_id')
                    ->where('orders_id', '=', $order_id)->get();

                Session::forget('discout_promo_bath');
                Session::forget('discout_promo_per');
                Session::forget('discount_code');
                Session::forget('orders_id');
                Session::forget('discount_id');
                Cart::destroy();
                $data = $order_id;
                $sendMail = 'true';
                return view('pages.cart-success', compact('data', 'order_vouchers', 'vouchers', 'order', 'order_id', 'member', 'sendMail'));
            }

        }
    }

    public function cartSuccessView($id){
        $orders = Orders::query()->find($id);
        $oder_id = $orders->id;
        $vouchers = DB::table('tb_voucher')
        ->leftjoin('tb_order_detail', 'tb_voucher.voucher_id', '=', 'tb_order_detail.voucher_id')
        ->leftjoin('tb_order', 'tb_order_detail.order_id', '=', 'tb_order.id')
        ->where('tb_order.id', '=', $id)->get();

        $order_details = DB::table('tb_order')
        ->leftJoin('tb_order_detail', 'tb_order_detail.order_id', '=', 'tb_order.id');
        $order_details = $order_details->where('tb_order.id', $id);
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
       $getAddressOrder = \App\LogAddressOrder::query()->where('ref_order_id',$id)->first();
        $value = json_decode($getAddressOrder->json_address);
        $getProvince = DB::table('provinces')->where('id',$value->provinces_id)->first();
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


        $address = $value->address_member . " " . $d . $value->districts_id . " " . $a . $value->amphures_id . " จ." . $p_name. " " . $value->zip_code;
        $name = $value->name_member.' '.$value->lastname_member;    
          return view('pages.cart-success', compact('id', 'vouchers', 'orders', 'oder_id', 'order_details', 'status_order2','address', 'name'));

    }

    public function fecthAmphur(Request $request)
    {
        $select = $request->get('select');
        $result = array();
        $amphurs = DB::table('provinces')
            ->leftjoin('amphures', 'amphures.province_id', '=', 'provinces.id')
            ->select('amphures.name_th', 'amphures.id')
            ->where('provinces.id', $select)
            ->get();
        $output = '<option value="0">เลือกอำเภอของท่าน</option>';
        foreach ($amphurs as $amphur) {

            $output .= '<option value="' . $amphur->id . '">' . $amphur->name_th . '</option>';
        }
        echo $output;
    }

    public function fecthDistricts(Request $request)
    {
        $select = $request->get('select');
        $result = array();
        $districts = DB::table('amphures')
            ->leftjoin('districts', 'districts.amphure_id', '=', 'amphures.id')
            ->select('districts.name_th', 'districts.id')
            ->where('amphures.id', $select)
            ->get();
        $output = '<option value="0">เลือกตำบลของท่าน</option>';
        foreach ($districts as $district) {
            $output .= '<option value="' . $district->id . '" >' . $district->name_th . '</option>';
        }
        echo $output;
    }

    public function fecthPostcode(Request $request)
    {
        $select = $request->get('select');
        $result = array();
        $postcodes = DB::table('districts')
            ->select('zip_code')
            ->where('id', $select)
            ->get();
        $output = '';
        foreach ($postcodes as $postcodes) {
            $output .= $postcodes->zip_code;
        }
        echo $output;
    }

    public function checkCodeNew($code, $id)
    {
        $date = date('Y-m-d');
//        $date = time();

        $count_check = $code;
        $discount = Discount::query()
            ->leftjoin('main_voucher AS m', 'tb_discount.discount_main', '=', 'm.id_main')
            ->leftjoin('discount_vouchers AS v', 'tb_discount.discount_id', '=', 'v.ref_discount_id')
            ->where('tb_discount.discount_code', $count_check)->orWhere('v.discount_code_multiple', $count_check)->first();
        $res = [];
        $discount_main = explode(',', $discount->discount_main);
        $checkMain = 'false';
        $idMainInCart = [];
        foreach ($discount_main AS $v) {
            if (in_array($v, $id)) {
                $idMainInCart[] = $v;
                $checkMain = 'true';
            }
        }
        $p = 0;
        if (@count($idMainInCart) > 0) {
            foreach (Cart::content() AS $v) {
                if (in_array($v->options->main_id, $idMainInCart)) {
                    $p += ($v->price * $v->qty);
                }
            }
        }
        if ($discount) {
            $checkOrder = Orders::query()->where('user_id', Session::get('id_member'))->where('discount_code_order', $code)->first();
            if ($discount->status_used == 'yes') {
                $res['status'] = 'info';
                $res['message'] = 'code ส่วนลดนี้ถูกใช้งานแล้ว';
            } else if ($checkOrder) {
                $res['status'] = 'info';
                $res['message'] = 'code ส่วนลดนี้ท่านได้ใช้งานแล้วไม่สามารถใช้งานได้อีก';
            } else if ($discount->discount_qty < 1) {
                $res['status'] = 'info';
                $res['message'] = 'code ส่วนลดนี้ใช้งานครบกำหนดแล้ว';
            } else if ($date < $discount->date_start || $date > $discount->date_end) {
                $res['status'] = 'info';
                $res['message'] = 'code ส่วนลดนี้หมดเวลาใช้งานแล้ว';
            } else if ($discount->discount_main != 0 && $checkMain == 'false') {
                $res['status'] = 'info';
                $res['message'] = 'ไม่สามารถใช้ส่วนลดโรงแรมนี้ได้';
            } else {
                $total = (int)str_replace(',', '', Cart::total());
                if ($total < $discount->discount_min) {
                    $res['status'] = 'info';
                    $res['message'] = 'ต้องซื้อ Voucher ขั้นต่ำ ' . $discount->discount_min;
                } else if ($p > 0 && $discount->discount_min > $p) {
                    $res['status'] = 'info';
                    $res['message'] = 'ต้องซื้อ Voucher promotion ขั้นต่ำ ' . $discount->discount_min;
                } else {
                    session([
                        'discout_promo_bath' => $discount->discount_bath,
                        'discout_promo_per' => 0,
                        'discount_id' => $discount->discount_id,
                        'discount_code' => $count_check
                    ]);
                    Session::save();
                    $res['status'] = 'success';
                    $res['message'] = 'code ส่วนลดนี้สามารถใช้งานได้';
                    $res['discount'] = number_format($discount->discount_bath);
                    $res['total'] = number_format($total);
                    $res['grandTotal'] = number_format(($total - $discount->discount_bath));

                }
            }


        } else {
            $res['status'] = 'info';
            $res['message'] = 'code ส่วนลดนี้ไม่สามารถใช้งานได้';
        }
        return $res;
    }

    public function checkCode(Request $request)
    {

        $id = [];
        foreach (Cart::content() AS $k => $v) {
            $id[] = $v->options->main_id;
        }
//        $date = date('Y-m-d H:i:s');
        $count_check = $request->get('code');
        return $this->checkCodeNew($count_check, $id);

//        $result = array();
//        $discount = Discount::query()
//            // ->leftjoin('main_voucher','tb_discount.discount_main','=','main_voucher.id_main')
//            ->leftjoin('main_voucher', 'tb_discount.discount_main', '=', 'main_voucher.id_main')
//            ->where('discount_code', $count_check)
//            ->first();
//
//        if ($discount == null) {
//            $id_main = 999999;
//            $discount_main = 9999999;
//        } else {
//            $id_main = $discount->id_main;
//            $discount_main = $discount->discount_main;
//        }
//        $contents = Cart::content();
//        $id_check = 0;
//        $check_qty = 0;
//        foreach ($contents as $key => $content) {
//            $voucher = DB::table('tb_voucher')->where('voucher_id', $content->id)->first();
//            // echo $voucher->relation_mainid.'/'.$id_main."<br/>";
//            if ($voucher->relation_mainid == $id_main) {
//                $id_check += 1;
//            } else if ($discount_main == 0) {
//                $id_check += 1;
//            }
//
//            $check_qty = $content->qty;
//        }
//
//
//        $total = (int)str_replace(',', '', Cart::total());
//        if (count(Cart::content()) > 1 || $check_qty > 1) {
//            return response()->json([
//                'discout_promo_bath' => number_format($total, 0, "", ","),
//                'condition' => 7
//            ]);
//        } else {
//            if ($discount->discount_qty <= 0) {
//                session([
//                    'discout_promo_bath' => number_format($total, 0, "", ","),
//                    'discout_promo_per' => 0
//                ]);
//                return response()->json([
//                    'discout_promo_bath' => Session::get('discout_promo_bath'),
//                    'condition' => 8
//                ]);//ไม่มีโปรโมชั่นนี้
//            } else {
//                if ($id_check <= 0) {
//                    return response()->json([
//                        'discout_promo_bath' => number_format($total, 0, "", ","),
//                        'condition' => 3
//                    ]);
//                } else {
//                    if (($discount != null) && ($discount->discount_qty != 0)) {
//                        // return response()->json([
//                        //     'discout_promo_bath' => $date,
//                        //     'condition'=>  $discount->date_start
//                        // ]);
//                        $count_check = DB::table('tb_order')->where([
//                            ['user_id', Session::get('id_member')],
//                            ['discount_id', $discount->discount_id],
//                        ])->whereIn('status_order', [001, 000])->get();
//
//                        session([
//                            'discount_id' => $discount->discount_id
//                        ]);
//
//                        if ($date < $discount->date_start) {
//                            return response()->json([
//                                'discout_promo_bath' => number_format($total, 0, "", ","),
//                                'condition' => 4
//                            ]);
//
//                        } else if ($date > $discount->date_end) {
//                            return response()->json([
//                                'discout_promo_bath' => number_format($total, 0, "", ","),
//                                'condition' => 5
//                            ]);
//                        } else {
//                            if ($total < $discount->discount_min) {
//                                return response()->json([
//                                    'condition' => 6,
//                                    'discout_promo_bath' => number_format($total, 0, "", ","),
//                                ]);
//                            } else {
//                                if (count($count_check) > 0) {
//                                    session([
//                                        'discout_promo_bath' => number_format($total, 0, "", ","),
//                                        'discout_promo_per' => 0,
//                                        'condition' => 1
//                                    ]);
//                                    return response()->json([
//                                        'discout_promo_bath' => Session::get('discout_promo_bath'),
//                                        'condition' => 1
//                                    ]);
//
//                                } else {
//                                    session([
//                                        'discout_promo_bath' => $discount->discount_bath,
//                                        // 'discout_promo_per' => $discount->discount_per
//                                    ]);
//
//                                    $discout_promo_bath = intval($discount->discount_bath);
//                                    session([
//                                        'total_bath' => ($total),
//                                        'discout_bath' => ($discout_promo_bath),
//                                        'total_end' => ($total - $discout_promo_bath)
//                                    ]);
//                                    return response()->json([
//                                        'total_bath' => $total,
//                                        'discout_promo_bath' => $total - $discout_promo_bath,
//                                        'discout_bath' => $discout_promo_bath,
//                                        'condition' => 2
//                                    ]);//ผู้ใช้งานยังไม่ใช้โค้ดนี้
//                                }
//                            }
//                        }
//                    } else {
//                        session([
//                            'discout_promo_bath' => number_format($total, 0, "", ","),
//                            'discout_promo_per' => 0
//                        ]);
//                        return response()->json([
//                            'discout_promo_bath' => Session::get('discout_promo_bath'),
//                            'condition' => 3
//                        ]);//ไม่มีโปรโมชั่นนี้
//
//                    }
//                }
//            }
//
//        }

    }

    public function showdetail($id)
    {
        $voucher = DB::table('tb_voucher')
            ->leftjoin('tb_blog', 'tb_voucher.relation_blogid', '=', 'tb_blog.id_blog')
            ->where('voucher_id', '=', $id)
            ->first();
        $facilityid = str_replace("", '"', $voucher->relation_facilityid);

        $facilities = DB::table('tb_facilities')
            ->whereIn('id_facilities', [1, 2, 3, 4])
            ->get();
        $data = array(
            'voucher' => $voucher,
            'facilities' => $facilities
        );
        return view('pages.showdetail', $data);
    }

    public function ConfirmationOrder($token)
    {
        Orders::where('comfirm_payment', $token)
            ->update([
                'is_comfirm' => 1
            ]);


        return redirect('cart')->with('success', 'ยืนยันการสั่งซื้อสินค้า เรียบร้อย!! ');
    }

    public function Cartsuccess($id)
    {
        $data = array(
            'id' => $id
        );

        // return redirect(route('cart_index',$id))->with('success','ยืนยันการสั่งซื้อสินค้า เรียบร้อย!! ตรวจสอบอีเมล์เพื่อรับ รายละเอียดของ Voucher');
    }

    public function check_limit($id, $qty)
    {
        $voucher = Voucher::find($id);

        if ($qty >= $voucher->qty_voucher) {
            return 1;
        } else {
            return 0;
        }

    }

}