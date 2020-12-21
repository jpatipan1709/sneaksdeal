<?php

namespace App\Http\Controllers\admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class loginController extends Controller
{
    public function getIndex()
    {
        if (Auth::check()) {
            return redirect('/admin/index');
        } else {
            return view('admin.login');
        }
    }

    // ตรวจสอบค่าที่ส่งมาจาก Form login แล้วเรียนกใช้การ validate จาก LoginRequest
    public function postProcess(LoginRequest $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        if (Auth::attempt(['username' => $username, 'password' => $password, 'type' => 'admin'], $request->has('remember'))) {
            return redirect()->intended('/admin/index');
        } else {
            return redirect()->back()->with('message', "Error!! Username or Password Incorrect. \nPlease try again.");;
        }
    }
    public function getLogout(){
        Auth::logout();
        return redirect('/admin/login');
    }
}
