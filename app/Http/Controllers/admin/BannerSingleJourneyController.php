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
use App\Model\BannerSingleJourney;
use App\Model\admin\SystemFileModel;

class BannerSingleJourneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.banner_single_journey.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backoffice.banner_single_journey.create');
    }


    public function store(Request $request)
    {
        $link = $_REQUEST['link'];
        $fileName = insertSingleImage($request, 'banner_single_journey');
        $data = new BannerSingleJourney;
        $data->file_name = $fileName;
        $data->link = $link;
        $data->save();
        // alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../banner');
        return redirect('/backoffice/banner_single_journey')->with('success', 'เพิ่มรูปภาพ แบนเนอร์เรียบร้อยแล้ว');


    }


    public function destroy($id)
    {
        $row = BannerSingleJourney::find($id);
        Storage::delete('banner_single_journey/' . $row->file_name);
        BannerSingleJourney::destroy($id);
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable()
    {
        $data = BannerSingleJourney::query()->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('IMG', function ($data) {
                $img = '<img style="width:200px;height:100px" src="' . url("storage/banner_single_journey/" . $data->file_name) . '">';
                return $img;
            })->addColumn('Link', function ($data) {
                $img = '<a href="' . $data->link . '" target="_blank">' . $data->link . '</a>';
                return $img;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, false, false, true, "backoffice/banner_single_journey");
                return $Manage;
            })
            ->rawColumns(['No', 'IMG', 'Link', 'Manage'])
            ->make(true);
    }


}
