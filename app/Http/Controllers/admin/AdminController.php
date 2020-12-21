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


class AdminController extends Controller
{
    public function index()
    {
        return view('backoffice.admin.index');
    }


    public function create()
    {
        $main = MainVoucherModel::all();
        return view('backoffice.admin.create', compact('main'));
    }


    public function store(AdminRequests $request)
    {
        

            if ($request->stat_admin == 0) {
                $stat = 'AdminSneaksdeal';
            } else {
                $stat = 'AdminMainVoucher';
            }
            $fileName = insertSingleImage($request, 'admin');

            $admin = new SystemAdminModel();
            $admin->name_admin = $request->name;
            $admin->lastname_admin = $request->last_name;
            $admin->username_admin = $request->email;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->rePassword);
            $admin->main_id_at = $request->stat_admin;
            $admin->status_admin = $stat;
            $admin->file_img = $fileName;
            $admin->save();
            return redirect(url('/backoffice/admin'))->with('success','เพิ่มข้อมูล ผู้ดูแลระบบ เรียบร้อยแล้ว');


    }

    public function show($id)
    {
        dd("Test");
    }


    public function edit($id)
    {
        $main = MainVoucherModel::all();

        $data = SystemAdminModel::query()->
        leftJoin('main_voucher as b', 'system_admin.main_id_at', 'b.id_main')
        ->where('system_admin.id_admin', $id)->first();
        return view('backoffice.admin.edit', compact('data','main'));
    }


    public function update(UpdateAdminRequest $request, $id)
    {
//        dd($request);

            if ($request->stat_admin == 0) {
                $stat = 'AdminSneaksdeal';
            } else {
                $stat = 'AdminMainVoucher';
            }
            $getImg =  SystemAdminModel::where('id_admin', $id)->first();
            if($request->fileToUpload !='') {
                Storage::delete('admin/' . $getImg->file_img);
                $fileName = insertSingleImage($request, 'admin');
            }else{
                $fileName  =$getImg->file_img;
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

            return redirect(url('/backoffice/admin'))->with('success','แก้ไขข้อมูล ผู้ดูแลระบบ เรียบร้อยแล้ว');



    }


    public function destroy($id)
    {
        $row = SystemAdminModel::where('id_admin', $id)->first();
            Storage::delete('admin/' . $row->file_img);
        SystemAdminModel::destroy($id);
        messageSuccess('Delete success');
    }


    public function queryDatatable()
    {
        $data = SystemAdminModel::query()->leftJoin('main_voucher as b', 'system_admin.main_id_at', 'b.id_main')
            ->where('system_admin.id_admin','!=','1')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', '')
            ->addColumn('name', function ($data) {
                $name = $data->name_admin.' '.$data->lastname_admin;
                return $name;
            })
            ->addColumn('stat', function ($data) {
                if($data->main_id_at == 0){
                    $stat = 'Sneaksdeal';
                }else{
                    $stat = $data->name_main;
                }
                return $stat;
            })
            ->addColumn('img', function ($data) {
                if($data->file_img != ''){
                    $img = '<img src="'.url('storage/admin/'.$data->file_img).'" style="width:160px;height:160px">';
                }else{
                    $img = "";
                }
                return $img;
            })
            ->addColumn('Manage', function ($data) {
                if($data)
                $Manage = buttonManageData($data->id_admin, false, true, true, 'backoffice/admin');
                return $Manage;
            })
            ->rawColumns(['No','name','stat','img', 'Manage'])
            ->make(true);
    }

    public function showPDF(){
        $system_admin = DB::table('system_admin')
                        ->leftjoin('main_voucher','system_admin.main_id_at','=','main_voucher.id_main')
                        ->get();

        Excel::create(Carbon::now()->format('YmdHis').'_Process_data', function($excel) use($system_admin) {
           
            
            $excel->setTitle('Member List');
            $excel->setCreator('Me')->setCompany('Our Code World');
            $excel->setDescription('A demonstration to change the file properties');


                $excel->sheet('Sheet 1', function ($sheet) use ($system_admin) {
               
                $sheet->setOrientation('portrait');

                $sheet->mergeCells('A1:E1');
                $sheet->getStyle('A1:E1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->setCellValueByColumnAndRow(0, 1, "รายงานชื่อ ลูกค้า/สมาชิก");
                $sheet->cell('A1:E2', function($cell) use($system_admin) {
                    $cell->setFont(array(
                        'bold' =>  true,
                        'size' => 10
                    ));
                });
                $sheet->row(2, array(
                    '#',
                    'ชื่อ - นามสกุล',
                    'อีเมล์',
                    'สถานะ',
                    'โรงแรม',
                ));
               
                $sheet->getStyle('A2:E2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
               
                foreach ($system_admin as $key => $value) {
                    $sheet->row($key+3, array(
                        $key+1,
                        $value->name_admin.' '.$value->lastname_admin,
                        $value->email,
                        $value->status_admin,
                        $value->name_main,
                       
                    ));
                }
            });

        })->download('xlsx');
}
}
