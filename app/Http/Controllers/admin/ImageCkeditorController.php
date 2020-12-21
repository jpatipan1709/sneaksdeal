<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\admin\ImageCkeditorModel;

class ImageCkeditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.image_voucher.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backoffice.image_voucher.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = $_REQUEST['type'];
        $validateFile = Validator::make($request->all(), [
            'fileToUpload' => 'required|file|max:10240|mimes:jpeg,bmp,png,gif',
        ]);

        if ($validateFile->fails()) {
            messageError('!Error File max size 10MB , type jpeg,bmp,png,gif ,Not null');

        } else {
            $fileName = insertSingleImage($request, 'ckeditor');
            $data = ImageCkeditorModel::create([
                'type_menu' => $type,
                'name_img' => $fileName
            ]);
            if ($data) {
                alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../image_voucher');
            }
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



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = ImageCkeditorModel::where('ckeditor_id', $id)->first();
        Storage::delete('ckeditor/' . $row->name_banner);
        DB::table('image_ckeditor')->where('ckeditor_id', $id)->delete();
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable()
    {
        $data = ImageCkeditorModel::where('type_menu','voucher')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('IMG', function ($data) {
                $img = '<img style="width:200px;height:100px" src="' . url("storage/ckeditor/" . $data->name_img) . '">';
                return $img;
            })
            ->addColumn('link', function ($data) {
                $link = '<div class="col-md-12">
<input type="text" class="form-control" value="' . url("storage/ckeditor/" . $data->name_img) . '" id="valueCopy'.$data->ckeditor_id.'">
<br><center><button class="btn-flat btn btn-info" onclick="copyText('.$data->ckeditor_id.')">Copy link</button></center></div>
';
                return $link;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->ckeditor_id, false, false, true, "backoffice/image_voucher");
                return $Manage;
            })
            ->rawColumns(['No', 'IMG','link', 'Manage'])
            ->make(true);
    }
}
