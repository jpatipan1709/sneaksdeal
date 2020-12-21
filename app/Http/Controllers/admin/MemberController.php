<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Member;
use Validator;
use DB;
use Datatables;
use Storage;
use Excel;
use App\Http\Controllers\PHPExcel_Style_Alignment;
use Carbon\Carbon;
use Session;
use App\Http\Requests\admin\MemberRequests;
use App\Http\Requests\admin\UpdateMemberRequests;


class MemberController extends Controller
{
    public function index()
    {
        return view('backoffice.member.index');
    }


    public function create()
    {
        return view('backoffice.member.create');
    }


    public function store(MemberRequests $request)
    {


        $member = new Member;
        $member->email = $request->email;
        $member->password = bcrypt($request->rePassword);
        $member->tel_member = $request->tel;
        $member->name_member = $request->name;
        $member->lastname_member = $request->last_name;
        $member->is_activate = 1;
        $member->save();
        return redirect(route('member.index'))->with('success', 'เพิ่มข้อมูล สมาชิกเรียบร้อยแล้ว');


    }

    public function show($id)
    {
//        $data['data'] = TypeBlogModel::where('id_facilities', $id)->first();
//        return view('backoffice.type_blog.show', $data);

    }


    public function edit($id)
    {
        $data = Member::where('id_member', $id)->first();
        return view('backoffice.member.edit', compact('data'));
    }


    public function update(UpdateMemberRequests $request, $id)
    {

        Member::where('id_member', $id)->update(
            [
                'email' => $request->email,
                'name_member' => $request->name,
                'lastname_member' => $request->last_name,
                'tel_member' => $request->tel
            ]);
        if ($request->rePassword != '' && $request->password != '') {
            Member::where('id_member', $id)->update(
                [
                    'password' => bcrypt($request->rePassword)
                ]);
        }

        return redirect(route('member.index'))->with('success', 'แก้ไขข้อมูล สมาชิกเรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        DB::table('type_blog')->where('type_blogid', $id)->delete();
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    public function queryDatatable()
    {
        $i = 0;
        $data = Member::query();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', '')
            ->addColumn('name', function ($data) {
                $name = $data->name_member . ' ' . $data->lastname_member;
                return $name;
            })
            ->addColumn('stat', function ($data) {
                if ($data->facebook_id != '') {
                    $stat = '<a class="btn btn-primary" title="login ผ่าน Facebook" style="color:white"><i class="fab fa-facebook-square"></i></a>';
                } else if ($data->google_id != '') {
                    $stat = '<a class="btn btn-danger" title="login ผ่าน Google" style="color:white"><i class="fab fa-google-plus"></i></a>';

                } else {
                    $stat = '<a class="btn btn-warning" title="login ผ่าน Email" style="color:white"><i class="fas fa-envelope"></i></a>';

                }
                return $stat;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_member, false, true, false, 'backoffice/member');
                return $Manage;
            })
            ->rawColumns(['No', 'name', 'stat', 'Manage'])
            ->make(true);
    }

    public function showPDF(){
            $members = DB::table('tb_member')
                        ->leftjoin('provinces','tb_member.provinces_id','=','provinces.id')
                        ->get();

            Excel::create(Carbon::now()->format('YmdHis').'_Process_data', function($excel) use($members) {
               
                
                $excel->setTitle('Admin List');
                $excel->setCreator('Me')->setCompany('Our Code World');
                $excel->setDescription('A demonstration to change the file properties');
    
    
                    $excel->sheet('Sheet 1', function ($sheet) use ($members) {
                   
                    $sheet->setOrientation('portrait');
    
                    $sheet->mergeCells('A1:F1');
                    $sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->setCellValueByColumnAndRow(0, 1, "รายงานชื่อ ผู้ใช้งานระบบ");
                    $sheet->cell('A1:F2', function($cell) use($members) {
                        $cell->setFont(array(
                            'bold' =>  true,
                            'size' => 10
                        ));
                    });
                    $sheet->row(2, array(
                        '#',
                        'ชื่อ - นามสกุล',
                        'อีเมล์',
                        'ที่อยู่',
                        'เบอร์โทรศัพท์',
                    ));
                   
                    $sheet->getStyle('A2:F2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                   
                    foreach ($members as $key => $value) {
                        $sheet->row($key+3, array(
                            $key+1,
                            $value->name_member.' '.$value->lastname_member,
                            $value->email,
                            $value->address_member.' '.$value->districts_id.' '.$value->amphures_id.' '.$value->name_th.' '.$value->zip_code,
                            $value->tel_member,
                           
                        ));
                    }
                });
    
            })->download('xlsx');
    }
}
