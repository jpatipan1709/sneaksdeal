<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\admin\ImageCkeditorModel;
use App\Model\Album;

class ImageCkeditor2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $album = @$request->album;
        if ($request->date == '') {
            $date = '';
        } else if ($request->date && $request->date != '') {
            $date = $request->date;
        } else {
            $date = date('d-m-Y');
        }
        $getData = Album::query()->orderBy('name_album', 'ASC')->get();
        $option = '<option value="" >ทั้งหมด</option>';
        foreach ($getData as $v) {
            $option .= '<option value="' . $v->id . '"  ' . ($album == $v->id ? 'selected' : '') . '>' . $v->name_album . '</option>';
        }
        return view('backoffice.image_blog.index', compact('option', 'album', 'date'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $getData = Album::query()->orderBy('name_album', 'ASC')->get();
        $option = '<option value="" >กรุณาเลือกอัลบั้ม</option>';
        foreach ($getData as $v) {
            $option .= '<option value="' . $v->id . '" >' . $v->name_album . '</option>';
        }
        return view('backoffice.image_blog.create', compact('option'));
    }

    public function addAlbum(Request $request)
    {
        $name = @$request->name_album;
        $check = Album::query()->where('name_album', 'LIKE', "'%" . $name . "%'")->first();
        if ($check) {
            $stat = 'false';
        } else {
            $data = new Album();
            $data->name_album = $name;
            $data->save();
            $stat = 'true';
        }
        $getData = Album::query()->orderBy('name_album', 'ASC')->get();
        $data = [
            'status' => $stat,
            'data' => $getData,
        ];
        return response()->json($data);
    }

    public function delAlbum($id){
        Album::destroy($id);
        $getData = Album::query()->orderBy('name_album', 'ASC')->get();
        $data = [
            'status' => 'true',
            'data' => $getData,
        ];
        return response()->json($data);
    }

    public function updateAlbum(Request $request, $id)
    {
        Album::query()->where('id', $id)->update([
            'name_album' => $request->name_album
        ]);
        $stat = 'true';
        $getData = Album::query()->orderBy('name_album', 'ASC')->get();
        $data = [
            'status' => $stat,
            'data' => $getData,
        ];
        return response()->json($data);
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
        $album = $request->album;
        $validateFile = Validator::make($request->all(), [
            'fileToUpload' => 'required|file|max:10240|mimes:jpeg,bmp,png,gif',
        ]);

        if ($validateFile->fails()) {
            messageError('!Error File max size 10MB , type jpeg,bmp,png,gif ,Not null');
        } else {
            $fileName = insertSingleImage($request, 'ckeditor');
            $data = ImageCkeditorModel::create([
                'type_menu' => $type,
                'name_img' => $fileName,
                'ref_id_album' => $album
            ]);
            if ($data) {
                return redirect(url('backoffice/image_blog'))->with('success', 'เพิ่มข้อมูล รูปภาพ Image blog เรียบร้อยแล้ว');
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
        $getData = Album::query()->orderBy('name_album', 'ASC')->get();
        return view('backoffice.image_blog.show', compact('getData'));
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
    public function queryDatatable(Request $request)
    {
        $album = @$request->album;
        $date = @postDate($request->date);
        $data = ImageCkeditorModel::where('type_menu', 'blog');
        if ($album != '') {
            $data = $data->where('ref_id_album', $album);
        }
        if ($request->date != '') {
            $data = $data->whereRaw("DATE(created_at) = '" . $date . "'");
        }
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('IMG', function ($data) {
                $img = '<img style="width:200px;height:100px" src="' . url("storage/ckeditor/" . $data->name_img) . '">';
                return $img;
            })
            ->addColumn('link', function ($data) {
                $link = '<div class="col-md-12">
<input type="text" class="form-control" value="' . url("storage/ckeditor/" . $data->name_img) . '" id="valueCopy' . $data->ckeditor_id . '">
<br><center><button class="btn-flat btn btn-info" onclick="copyText(' . $data->ckeditor_id . ')">Copy link</button></center></div>
';
                return $link;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->ckeditor_id, false, false, true, "backoffice/image_blog");
                return $Manage;
            })
            ->rawColumns(['No', 'IMG', 'link', 'Manage'])
            ->make(true);
    }
}
