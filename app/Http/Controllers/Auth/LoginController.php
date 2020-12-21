<?php


namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Redirect;
use App\Model\admin\SystemAdminModel;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Requests\Storelogin;
use App\Member;
use App\User;
use App\Order_voucher;
use Session;
use DB;
use Cart;
use App\Model\admin\BannerModel;
use App\Model\admin\VoucherModel;
use App\Model\admin\SystemFileModel;
use App\Model\admin\MainVoucherModel;
use App\Model\admin\SelectVoucherModel;

class LoginController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Login Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles authenticating users for the application and

    | redirecting them to your home screen. The controller uses a trait

    | to conveniently provide its functionality to your applications.

    |

    */


    use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/backoffice/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */


    // public function __construct()

    // {

    //     $this->middleware('guest')->except('logout');

    // }


    public function redirectToProvider()

    {

        return Socialite::driver('google')->redirect();

    }


    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */

    public function handleProviderCallback()

    {

        $user = Socialite::driver('google')->stateless()->user();

        $findUser = Member::where('email', $user->email)->first();


        // dd($findUser);

        if ($findUser) {

            $findUser = Member::where('email', $user->email)->first();

            if (Auth::guard('login')->attempt(['email' => $findUser->email, 'password' => 123456])) {
                session(['email' => $findUser->email]);
                session(['name_member' => $findUser->name_member]);
                session(['lastname_member' => $findUser->lastname_member]);
                session(['id_member' => $findUser->id_member]);
                session(['tel_member' => $findUser->tel_member]);
                session(['facebook_id' => $findUser->facebook_id]);
                session(['google_id' => $findUser->google_id]);
                session(['is_activate_hoitwork' => $findUser->is_activate_hoitwork]);
                Session::save();
                if ((Cart::count() == 0) || (Cart::count() == null)) {
                    return redirect('voucherbrowsing');
                } else {
                    return redirect('cart');
                }
            } else {
                $error = "กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!";
                $data = array(
                    'error' => $error,
                );
                return redirect(url('/sign-in'))->with('danger','มีบัญชีผู้ใช้งาน นี้อยู่แล้ว!! กรุณาล็อกอินแบบลงทะเบียน');
            }
        } else {
            $newUser = new Member;
            $newUser->email = $user->email;
            $newUser->name_member = $user->name;
            $newUser->password = bcrypt(123456);
            $newUser->avatar = $user->avatar;
            $newUser->google_id = $user->id;
            $newUser->save();
            $findUser = Member::where('email', $user->email)->first();
            if (Auth::guard('login')->attempt(['email' => $findUser->email, 'password' => 123456])) {
                session(['email' => $findUser->email]);
                session(['name_member' => $findUser->name_member]);
                session(['lastname_member' => $findUser->lastname_member]);
                session(['id_member' => $findUser->id_member]);
                session(['tel_member' => $findUser->tel_member]);
                session(['facebook_id' => $findUser->facebook_id]);
                session(['google_id' => $findUser->google_id]);
                session(['is_activate_hoitwork' => $findUser->is_activate_hoitwork]);
                Session::save();
                if ((Cart::count() == 0) || (Cart::count() == null)) {
                    return redirect('/howitwork');
                } else {
                    return redirect('cart');
                }
            } else {
                $error = "กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!";
                $data = array(
                    'error' => $error,
                );
                return redirect(url('/sign-in'))->with('danger','กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!');
            }
        }
    }


    public function redirectToProvider2()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function handleProviderCallback2()

    {
        $user = Socialite::driver('facebook')->stateless()->user();
        $findUser = Member::where('email', $user->email)->first();
        if ($findUser) {
            $findUser = Member::where('email', $user->email)->first();
            if (Auth::guard('login')->attempt(['email' => $findUser->email, 'password' => 123456])) {
                session(['email' => $findUser->email]);
                session(['name_member' => $findUser->name_member]);
                session(['lastname_member' => $findUser->lastname_member]);
                session(['id_member' => $findUser->id_member]);
                session(['tel_member' => $findUser->tel_member]);
                session(['facebook_id' => $findUser->facebook_id]);
                session(['google_id' => $findUser->google_id]);
                session(['is_activate_hoitwork' => $findUser->is_activate_hoitwork]);
                Session::save();
                if ((Cart::count() == 0) || (Cart::count() == null)) {
                    return redirect('voucherbrowsing');
                } else {
                    return redirect('cart');
                }
            } else {
                $error = "มีอีเมล์นี้ในระบบแล้ว";
                $data = array(
                    'error' => $error,
                );
                return redirect(url('/sign-in'))->with('danger','มีบัญชีผู้ใช้งาน นี้อยู่แล้ว!! กรุณาล็อกอินแบบลงทะเบียน');
            }

        } else {

            $newUser = new Member;

            $newUser->email = $user->email;

            $newUser->name_member = $user->name;

            $newUser->password = bcrypt(123456);

            $newUser->avatar = $user->avatar;

            $newUser->facebook_id = $user->id;

            $newUser->save();


            $findUser = Member::where('email', $user->email)->first();


            if (Auth::guard('login')->attempt(['email' => $findUser->email, 'password' => 123456])) {
                session(['email' => $findUser->email]);
                session(['name_member' => $findUser->name_member]);
                session(['lastname_member' => $findUser->lastname_member]);
                session(['id_member' => $findUser->id_member]);
                session(['tel_member' => $findUser->tel_member]);
                session(['facebook_id' => $findUser->facebook_id]);
                session(['google_id' => $findUser->google_id]);
                session(['is_activate_hoitwork' => $findUser->is_activate_hoitwork]);
                Session::save();
                if ((Cart::count() == 0) || (Cart::count() == null)) {
                    return redirect('/howitwork');
                } else {
                    return redirect('cart');
                }

            } else {

                $error = "กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!";

                $data = array(

                    'error' => $error,

                );


                return redirect(url('/sign-in'))->with('danger','กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!');

            }

        }


    }


    public function store(Storelogin $request)
    {

        $findMemer = Member::where('email', $request->email)->first();

        if($findMemer != null){
            if($findMemer->is_activate == 0){
                return Redirect::back()->with('danger','กรุณายืนยันตัวตน โปรดตรวจสอบการยืนยันตัวตนในอีเมล์ของท่าน');
            }else{
                $credentials = $request->only('email', 'password');
                if (Auth::guard('login')->attempt($credentials)) {
                    $findUser = Member::where('email', $request->email)->first();
                    session(['email' => $findUser->email]);
                    session(['name_member' => $findUser->name_member]);
                    session(['lastname_member' => $findUser->lastname_member]);
                    session(['id_member' => $findUser->id_member]);
                    session(['tel_member' => $findUser->tel_member]);
                    session(['facebook_id' => $findUser->facebook_id]);
                    session(['google_id' => $findUser->google_id]);
                    session(['is_activate_hoitwork' => $findUser->is_activate_hoitwork]);
                    Session::save();
                    $banner = BannerModel::all();
                    $voucher = SelectVoucherModel::query()
                        ->leftJoin('main_voucher', 'select_voucher.main_join', '=', 'main_voucher.id_main')
                        ->leftJoin('tb_voucher', 'select_voucher.voucher_id_join', '=', 'tb_voucher.voucher_id')
                        ->get();
                        if ((Cart::count() == 0) || (Cart::count() == null)) {
                            return redirect(url('/voucherbrowsing'));
                        }else{
                            return redirect(url('/cart'));
                        }
                        
                        
                } else {
                    $error = "กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!";
                    $data = array(
                        'error' => $error,
                    );
                    return redirect(url('/sign-in'))->with('danger','กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!');
                }
            }
        }else{
            return Redirect::back()->with('danger','กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!');
        }
        


    }


    public function loginBackoffice(Storelogin $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('login')->attempt($credentials)) {
            $findUser = SystemAdminModel::where('email_admin', $request->email)->first();
            session(['name_admin' => $findUser->name_admin]);
            session(['lastname_admin' => $findUser->lastname_admin]);
            session(['id_admin' => $findUser->id_admin]);

            return view('backoffice.dashboard');
        } else {
            $error = "กรุณาตรวจสอบ อีเมล์ และ รหัสผ่าน ของท่านใหม่!";
            $data = array(
                'error' => $error,
            );
            return view('backoffice.login', $data);

        }

    }

    public function logout2()
    {

        Auth::logout();

        Session::flush();

        return redirect('/sign-in');

    }

}

