<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Datatables;
use Storage;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.filter.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $stat = $request->stat;
        $up =  DB::table('tb_filter')
            ->where('id_filter', $id)
            ->update(['stat_show' => $stat]);

        if($up){
            messageSuccess('เปลี่ยนสถานะการแสดงสำเร็จ');
        }else{
            messageError('เปลี่ยนสถานะการแสดงล้มเหลว');

        }
    }


    //index
    public function queryDatatable()
    {
        $data = DB::table('tb_filter')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Manage', function ($data) {
                $Manage = '<center><label class="switch">
                                    <input type="checkbox" onchange="changeShowFilter($(this))" name="show" value="' . $data->id_filter . '" ' . ($data->stat_show == 'y' ? 'checked' : '') . ' id="switch">
                                    <span class="slider round"></span>
                                </label></center>';
                return $Manage;
            })
            ->rawColumns(['No', 'Manage'])
            ->make(true);
    }
}
