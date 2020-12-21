<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\BannerRequests;
use App\Http\Requests\admin\LogoRequests;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\admin\BannerModel;
use App\Model\admin\SystemFileModel;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.banner.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backoffice.banner.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequests $request)
    {
  
            $link = $_REQUEST['link'];



            $fileName = insertSingleImage($request, 'banner');
            DB::table('tb_banner')->insert([
                ['name_banner' => $fileName, 'link_banner' => $link]
            ]);
            // alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../banner');
            return redirect(url('/backoffice/banner'))->with('success','เพิ่มรูปภาพ แบนเนอร์เรียบร้อยแล้ว');


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('backoffice.banner.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = FacilitiesModel::where('id_facilities', $id)->first();
        return view('backoffice.facilities.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->file('fileToUpload')) {
            $validator = Validator::make($request->all(), [
                'fileToUpload' => 'file|max:10240|mimes:jpeg,bmp,png,gif',

            ]);
            if ($validator->fails()) {
                messageError('!Error File max size 10MB , type jpeg,bmp,png,gif ,Not null');

            } else {
                $row = FacilitiesModel::where('id_facilities', $id)->first();
                Storage::delete('facilities/' . $row->icon_facilities);

                $fileName = insertSingleImage($request, 'facilities');
                $data = DB::table('tb_facilities')->where('id_facilities', $id)->update([
                    'name_facilities' => $_REQUEST['nameFacilities'],
                    'icon_facilities' => $fileName
                ]);
                if ($data) {
                    alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../../facilities');
                } else {
                    messageError('!Error Update');
                }
            }
        } else {
            $data = DB::table('tb_facilities')->where('id_facilities', $id)->update(['name_facilities' => $_REQUEST['nameFacilities']]);
            if ($data) {
                return redirect(url('/backoffice/banner'))->with('success','แก้ไขข้อมูล เรียบร้อยแล้ว');
            } else {
                messageError('!Error Update');

            }
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = BannerModel::where('banner_id', $id)->first();
        Storage::delete('banner/' . $row->name_banner);
        DB::table('tb_banner')->where('banner_id', $id)->delete();
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable()
    {
        $data = BannerModel::query();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('IMG', function ($data) {
                $img = '<img style="width:200px;height:100px" src="' . url("storage/banner/" . $data->name_banner) . '">';
                return $img;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->banner_id, false, false, true, "backoffice/banner");
                return $Manage;
            })
            ->rawColumns(['No', 'IMG', 'Manage'])
            ->make(true);
    }

    public function  updatelogo(LogoRequests $request){
        // dd($request->file('fileToUpload')->getClientOriginalName());
        $row = SystemFileModel::where('relationTable', 'logo')->first();

        if($row != null){
            // dd("TRUE");
            Storage::delete('logo/' . $row->name);
            $fileName = insertSingleImage($request, 'logo');
            $data = DB::table('system_file')->where('relationTable', 'logo')->update([
                'name' => $fileName,
            ]);
            if ($data) {
                return redirect(url('/backoffice/banner'))->with('success','บันทึกข้อมูล เรียบร้อยแล้ว');
            } else {
                messageError('!Error Update');
            }
        }else{
            // dd("FAIL");
            $fileName = insertSingleImage($request, 'logo');
            $data = DB::table('system_file')->where('relationTable', 'logo')->insert([
                 'name' => $fileName,
                 'relationTable' => 'logo',
            ]);
            if ($data) {
                return redirect(url('/backoffice/banner'))->with('success','แก้ไขข้อมูล เรียบร้อยแล้ว');
            } else {
                messageError('!Error Update');
            }
        }
       

  


        // $system_file = DB::table('system_file')->where('relationTable','=','logo')->first();
        // if($system_file == null){
        //     $system_files = new App\admin\SystemFileModel;
        //     $system_files-> = $request->
        // }
    }
}
