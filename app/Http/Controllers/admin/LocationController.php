<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.location.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backoffice.location.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $location = new Location();
        $location->name_location = $name;
        $location->save();
        return redirect('/backoffice/location')->with('success', 'เพิ่มข้อมูลสำเร็จ');


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('backoffice.location.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Location::query()->find($id);
        return view('backoffice.location.edit', compact('data', 'id'));
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
        $name = $request->name;
        Location::query()->where('id',$id)->update(['name_location' => $name]);
        return redirect('/backoffice/location')->with('success', 'แก้ไขข้อมูลสำเร็จ');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Location::destroy($id);
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable()
    {
        $data = Location::query()->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id, false, true, true, "backoffice/location");
                return $Manage;
            })
            ->rawColumns(['No', 'Manage'])
            ->make(true);
    }


}
