<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Location;
use App\Model\admin\MainVoucherModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.voucherbrowsing');
    }

    public function search(){
            $getData = Location::query()->get();
            $getData2 = MainVoucherModel::query()->get();
            $data['LOCATION'] = $getData;
            $data['MAIN'] = $getData2;
            return response()->json($data);
    }
}
