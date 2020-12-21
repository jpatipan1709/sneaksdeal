<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Redirect;
use App\Member;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMember;
use Auth;
use Session;
use App\Model\admin\BannerModel;
use App\Model\admin\SelectVoucherModel;
use DB;
use Cart;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mail;
use App\Http\Requests\ChkAfterAccount;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function index()
    {
        return view('pages.register');
    }

    protected function store(ChkAfterAccount $request)
    {
  
      
            $token = str_random(25);
            $user= new Member;
            $user->email= $request->email;
            $user->password=  bcrypt($request->password);
            if($request->firstName != null ){
                $user->name_member= $request->firstName;
                $user->lastname_member= $request->lastName;
            }else{
                $user->name_member= $request->firstName2;
                $user->lastname_member= $request->lastName2;
            }
           
            $user->tel_member= $request->tel;
            $user->token =   $token;     
            $user->is_activate =   1;     
              
            $user->save();


            // Mail::send(['text' => 'mail'],['name','Patipan tongsang'],function($message){
            //     $message->to($request->email,$request->firstName.' '.$request->lastName)->subject('Welcome to https://wwww.sneaksdeal.com');
            //     $message->from('j.patipan.tongsang@gmail.com','Patipan tongsang');
            // });

            // $message = '<html>';
            // $message .= '<head>';
            // $message .= '<link href="https://fonts.googleapis.com/css?family=Prompt:400,500,700|Roboto:400,500,700" rel="stylesheet">';
            // $message .= '</head>';
            // $message .= '	<body>';
            // $message .= '		<style>';
            // $message .= '			body {';
            // $message .= '			    font-family: "Roboto", "Prompt", sans-serif;';
            // $message .= '			    color: #000;';
            // $message .= '			    text-align: center;';
            // $message .= '			}';
            // $message .= '			@media print {';
            // $message .= '			  body, page {';
            // $message .= '			    margin: 0;';
            // $message .= '			    box-shadow: 0;';
            // $message .= '			  }';
            // $message .= '			    .breaknewpage{';
            // $message .= '			    page-break-after: always;';
            // $message .= '			        }';
            // $message .= '			}';
            // $message .= '		</style>';
            
            // $message .= "	<div class=\"page_container\" style=\"padding: 15px 40px; max-width: 800px; width: 100%; background: #fbdc07; font-family: 'Roboto', 'Prompt', sans-serif;
            //     color: #000;\">";
            // $message .= "			    <img class=\"logo\" src=\"https://www.sneaksdeal.com/img/sneakoutlogo.jpg\"  style=\"margin: 0 auto 25px auto; width: 120px; height: auto; display: block;\">";
            // $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ท่านได้สมัครสมาชิกผ่านเว็บไซต์ Sneaksdeal<br>กรุณายืนยันตัวตนโดยการลิงก์ หรือปุ่มด้านล่าง</div>";
            // $message .= "			    <div style=\"font-size: 20px; text-align: center; margin: 30px 0;\">";
            // $message .= "			        <a style=\"display:inline-block;color:black;\" href=\"https://sneaksdeal.com/user/confirmation/$token\">คลิกที่นี่</a>";
            // $message .= '			    </div>';
            // $message .= "			    <div style=\"display:block; text-align:center; margin-top:15px; margin-bottom:15px;\">
            //             <a href=\"tel:+6667701732\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
            //                 <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/icon02.png\">
            //                 <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">08-677-01732</span>
            //             </a>
            //             <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
            //                 <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
            //                 <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">Sneaksdeal</span>
            //             </a>
            //             <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
            //                 <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
            //                 <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">sneakoutclub@gmail.com</span>
            //             </a>
                     
            //         </div>";
            // $message .= "			    <div style=\"border-bottom: 1px dashed #ccc; margin-top: 15px; margin-bottom: 25px;\"></div>";
            // $message .= "			    <div style=\"font-size: 11px; text-align: center;\">";
            // $message .= '			        <p>117 ถนนแฉล้มนิมิตร แขวงบางโคล่ เขตบางคอแหลม กรุงเทพ 10120</p>';
            // $message .= '			        <p>เว็บไซต์ : https://www.sneaksdeal.com</p>';
            // $message .= '			    </div>';
            // $message .= '			</div>';
            // $message .= '	</body>';
            // $message .= '</html>';

            // // echo $message;
            // try {
            //     $mail = new PHPMailer();
            //     $mail->IsSMTP();
            //     $mail->IsHTML(true);
            //     $mail->SMTPDebug  = 0;                  
            //     $mail->SMTPAuth = true;
            //     $mail->SMTPSecure = "tls";
            //     $mail->Host = "smtp.gmail.com";
            //     $mail->Port = 587;
            //     $mail->CharSet = "utf-8";
            //     $mail->SetFrom($request->email, $request->firstName.' '.$request->lastName); 
            //     $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
            //     $mail->Password = 'sneaksdeal1234';       //Pass Sent
            //     $mail->From  = 'sneaksdeal2018@gmail.com';    //User
            //     $mail->FromName = "https://sneaksdeal.com";
            //     $mail->Subject  = "กรุณายืนยันตัวตน ของท่านก่อนทำการเข้าใช้งานระบบ https://sneaksdeal.com ";
            //     $mail->Body  = $message;
            //     $mail->AddAddress($request->email);
            //     $mail->set('X-Priority', '3');
            //     $mail->Send();
            //     echo 'Message has been sent';
            // } catch (Exception $e) {
            //     echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            // }

            // return Redirect::back()->with('success','บันทึกเรียบร้อย โปรดยืนยันการสมัครสมาชิกในอีเมล์ของท่าน');

        $credentials = $request->only('email', 'password');
        if (Auth::guard('login')->attempt($credentials)) {
            $findUser = Member::where('email', $request->email)->first();
            session(['name_member' => $findUser->name_member]);
            session(['lastname_member' => $findUser->lastname_member]);
            session(['id_member' => $findUser->id_member]);
            Session::save();
            $filter = DB::table("tb_filter")->where('stat_show', 'y')->get();
            $banner = BannerModel::all();
            $voucher = SelectVoucherModel::query()
                ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
                ->where('tb_voucher.date_open', '<', date('Y-m-d H:i:s'))
                ->where('select_voucher.main_join', '!=', 0)
                ->orderBy('sort_by_view','asc')
                ->get();
    //        $SystemFileModel = SystemFileModel::where('relationTable','=','voucher')->get();
            // return view('pages.voucherbrowsing', compact('banner', 'voucher', 'filter'));
            return redirect('/howitwork');
        }
    }

    protected function comfirmation($token){
        $findUser = Member::where('token',$token)->first();
       
        if(!is_null($findUser)){
            $findUser->is_activate = 1;
            $findUser->token = '';
            $findUser->save();
            return redirect('/sign-in')->with('success','บัญชีของคุณ ทำการยืนยันตัวตนเรียบร้อย!!');
        }

        return redirect('/sign-in')->with('success','ไม่สามารถทำการ ยืนยันตัว โปรดติดต่อผู้ดูแล!!');
    }
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }
}
