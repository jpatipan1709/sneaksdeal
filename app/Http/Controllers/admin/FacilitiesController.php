<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\FacilitiesModel;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Http\Requests\Checkfacilitie;
class FacilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.facilities.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backoffice.facilities.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Checkfacilitie $request)
    {
        
        $name = $_REQUEST['nameFacilities'];

        $Facilities = FacilitiesModel::all();
        $validateFile = Validator::make($request->all(), [
            'fileToUpload' => 'required|file|max:10240|mimes:jpeg,bmp,png,gif',
        ]);
        $validateName = Validator::make($request->all(), [
            'nameFacilities' => 'required|unique:tb_facilities,name_facilities',
        ]);

        if ($validateFile->fails()) {
            messageError('!Error File max size 10MB , type jpeg,bmp,png,gif ,Not null');

        } else if ($validateName->fails()) {
            messageError('!Please key data');
            echo '<script>$("#id-input").focus();</script>';
        } else {
  
            $fileName = insertSingleImage($request, 'facilities');
            DB::table('tb_facilities')->insert([
                ['name_facilities' => $name, 'icon_facilities' => $fileName]
            ]);

            // alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../facilities');
            // echo '<script>window.location="'.url('backoffice/facilities').'"</script>';
            
            return redirect(url('/backoffice/facilities'))->with('success','เพิ่มข้อมูล สิ่งอำนวยความสะดวก เรียบร้อยแล้ว');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['data'] = FacilitiesModel::where('id_facilities', $id)->first();
        return view('backoffice.facilities.show', $data);

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
                    return redirect(url('/backoffice/facilities'))->with('success','แก้ไขข้อมูล สิ่งอำนวยความสะดวก เรียบร้อยแล้ว');
                } else {
                    return redirect(url('/backoffice/facilities'));
                }
            }
        } else {
            $data = DB::table('tb_facilities')->where('id_facilities', $id)->update(['name_facilities' => $_REQUEST['nameFacilities']]);
       
            if ($data) {
                return redirect(url('/backoffice/facilities'))->with('success','แก้ไขข้อมูล สิ่งอำนวยความสะดวก เรียบร้อยแล้ว');

            } else {
                
                return redirect(url('/backoffice/facilities'));

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
        $row = FacilitiesModel::where('id_facilities', $id)->first();
        Storage::delete('facilities/' . $row->icon_facilities);
        DB::table('tb_facilities')->where('id_facilities', $id)->delete();
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable()
    {
        $data = FacilitiesModel::query();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('IMG', function ($data) {
                $img = '<img style="width:50px;height:50px" src="' . url("storage/facilities/" . $data->icon_facilities) . '">';
                return $img;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_facilities, false, true, true, "backoffice/facilities");
                return $Manage;
            })
            ->rawColumns(['No', 'IMG', 'Manage'])
            ->make(true);
    }
}
