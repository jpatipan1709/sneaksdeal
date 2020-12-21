<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\LogoRequests;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\admin\SystemFileModel;

class SingleJourneyController extends Controller
{

    public function index()
    {
        $data =  DB::table('tb_single_journey')->first();
        return view('backoffice.single_journey.index',compact('data'));
    }


    public function store(Request $request)
    {
        $file =  DB::table('tb_single_journey')->first();

        $detail = $request->detail;
        if ($request->has('fileToUpload')) {
            Storage::delete('single_journey/'.$file->file_name);
            $fileName = insertSingleImage($request, 'single_journey');

        } else {
            $fileName = $file->file_name;
        }

        DB::table('tb_single_journey')->update([
            'file_name' => $fileName,
            'detail_name' => $detail,
        ]);
        // alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../banner');
        return redirect('/backoffice/single_journey')->with('success', 'อัพเดทข้อมูลเรียบร้อย');


    }


}
