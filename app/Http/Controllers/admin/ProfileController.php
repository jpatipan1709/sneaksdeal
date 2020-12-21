<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ProfileRequests;
use App\Model\admin\SystemAdminModel;
use App\Model\admin\MainVoucherModel;
use Validator;
use DB;
use Datatables;
use Storage;
use Session;

class ProfileController extends Controller
{
    public function index()
    {
        $id = Session::get('id_admin');
        if ($id != '') {
            $data = SystemAdminModel::query()->leftJoin('main_voucher AS m','system_admin.main_id_at','m.id_main')->where('system_admin.id_admin',$id)->first();
            return view('backoffice.profile.index',compact('data','id'));
        } else {
            return redirect(route('admin.login'));
        }
    }


    public function update(ProfileRequests $request)
    {
            $getImg = SystemAdminModel::where('id_admin', $request->id)->first();
            if ($request->fileToUpload != '') {
                Storage::delete('admin/' . $getImg->file_img);
                $fileName = insertSingleImage($request, 'admin');
            } else {
                $fileName = $getImg->file_img;
            }
            SystemAdminModel::where('id_admin', $request->id)->update(
                [
                    'name_admin' => $request->name,
                    'lastname_admin' => $request->last_name,
                    'file_img' => $fileName
                ]);
            session(['name_admin' => $request->name]);
            session(['lastname_admin' => $request->last_name]);
            session(['file_img_admin' => $fileName]);

            if ($request->rePassword != '' && $request->password != '') {
                SystemAdminModel::where('id_admin', $request->id)->update(
                    [
                        'password' => $request->rePassword
                    ]);
            }

            return redirect(url('backoffice/profile'))->with('success','แก้ไข profile เรียบร้อย!!');



    }


}
