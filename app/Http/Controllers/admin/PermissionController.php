<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\SystemAdminModel;
use App\Model\admin\MainVoucherModel;
use Validator;
use DB;
use Datatables;
use Storage;
use Excel;
use App\Http\Controllers\PHPExcel_Style_Alignment;
use Carbon\Carbon;
use App\Http\Requests\admin\AdminRequests;
use App\Http\Requests\admin\UpdateAdminRequest;


class PermissionController extends Controller
{
    public function index()
    {
        return view('backoffice.permission.index');
    }


    public function create()
    {

    }


    public function store(Request $request)
    {

        $menu = $request->menu;
        $admin = $request->idAdmin;
        $checkMenu = DB::table('tb_permission')->where('menu_id','=',$menu)->where('admin_id','=',$admin)->first();
        if(@$checkMenu->admin_id != ''){
            return redirect(url('backoffice/permission/'.$admin.'/edit'));
        }else{
          DB::table('tb_permission')->insert(
                [
                    'menu_id'=>$menu,
                    'admin_id'=>$admin
                ]
            );
            return redirect(url('backoffice/permission/'.$admin.'/edit'));

        }

    }

    public function show($id)
    {

    }


    public function edit($id)
    {

        $data = SystemAdminModel::query()
            ->where('id_admin', $id)->first();
        return view('backoffice.permission.edit', compact('data'));
    }


    public function update(UpdateAdminRequest $request, $id)
    {
//        dd($request);

        if ($request->stat_admin == 0) {
            $stat = 'AdminSneaksdeal';
        } else {
            $stat = 'AdminMainVoucher';
        }
        $getImg = SystemAdminModel::where('id_admin', $id)->first();
        if ($request->fileToUpload != '') {
            Storage::delete('admin/' . $getImg->file_img);
            $fileName = insertSingleImage($request, 'admin');
        } else {
            $fileName = $getImg->file_img;
        }
        SystemAdminModel::where('id_admin', $id)->update(
            [
                'email' => $request->email,
                'name_admin' => $request->name,
                'lastname_admin' => $request->last_name,
                'main_id_at' => $request->stat_admin,
                'status_admin' => $stat,
                'file_img' => $fileName
            ]);
        if ($request->rePassword != '' && $request->password != '') {
            SystemAdminModel::where('id_admin', $id)->update(
                [
                    'password' => $request->rePassword
                ]);
        }

        return redirect(url('/backoffice/admin'))->with('success', 'แก้ไขข้อมูล ผู้ดูแลระบบ เรียบร้อยแล้ว');


    }


    public function destroy($id)
    {
        DB::table('tb_permission')->where('id_per', '=', $id)->delete();
        messageSuccess('Delete success');
    }


    public function queryDatatable()
    {
        $data = SystemAdminModel::query()->where('id_admin', '!=', '1')->where('status_admin', '=', 'AdminSneaksdeal')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', '')
            ->addColumn('name', function ($data) {
                $name = $data->name_admin . ' ' . $data->lastname_admin;
                return $name;
            })
            ->addColumn('stat', function ($data) {
                if ($data->main_id_at == 0) {
                    $stat = 'Sneaksdeal';
                } else {
                    $stat = $data->name_main;
                }
                return $stat;
            })
            ->addColumn('img', function ($data) {
                if ($data->file_img != '') {
                    $img = '<img src="' . url('storage/admin/' . $data->file_img) . '" style="width:160px;height:160px">';
                } else {
                    $img = "";
                }
                return $img;
            })
            ->addColumn('Manage', function ($data) {
                if ($data)
                    $Manage = buttonManageData($data->id_admin, false, true, false, 'backoffice/permission');
                return $Manage;
            })
            ->rawColumns(['No', 'name', 'stat', 'img', 'Manage'])
            ->make(true);
    }


}
