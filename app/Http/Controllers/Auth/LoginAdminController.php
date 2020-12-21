<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;
use App\Admin;

use Session;


class LoginAdminController extends Controller
{
//    protected $redirectTo = '/backoffice/dashboard';
//     public function __construct()
//
//     {
//        $this->middleware('guest:admin',['except'=>'logout']);
//         $this->middleware('guest:admin')->except('logout');
//
//
//     }


    public function ShowLoginAdmin()
    {

        return view('backoffice.login');
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if (Auth::guard('web')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->remember)) {
            $findUser = Admin::where('email', $request->email)->first();
            session(['id_admin' => $findUser->id_admin]);
            session(['name_admin' => $findUser->name_admin]);
            session(['lastname_admin' => $findUser->lastname_admin]);
            session(['status_admin' => $findUser->status_admin]);
            session(['main_id_at' => $findUser->main_id_at]);
            session(['email_admin' => $findUser->email]);
            session(['file_img_admin' => $findUser->file_img]);
            session(['username_admin' => $findUser->username_admin]);

            if ($findUser->main_id_at == 0) {
                return redirect()->guest(route('dashboard'));
            } else if ($findUser->main_id_at > 0) {
                return redirect('backoffice/name-blog');

            } else {
                return redirect()->guest(route('admin.login'));
            }

//            return redirect('/backoffice/dashboard');

        }
        return redirect()->back()->withInput($request->only('email', 'remember'));


    }

    public function logout()
    {
        unsetAdmin();
        Auth::logout();

        return redirect('/backoffice/login');
    }

}