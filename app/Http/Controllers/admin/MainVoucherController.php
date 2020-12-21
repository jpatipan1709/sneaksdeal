<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\BlogModel;
use Validator;
use DB;
use Datatables;
use Storage;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\MainVoucherModel;
use App\Model\admin\TypeVoucher;
use App\Model\admin\VoucherModel;
use App\Model\admin\SystemFileModel;

class MainVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backoffice.main_voucher.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeVoucher = TypeVoucher::query()->get();
        return view('backoffice.main_voucher.create', compact('typeVoucher'));
    }

    public function changeSale(Request $request, $id)
    {
        $stat = $request->stat;

        $data = MainVoucherModel::where('id_main', $id)
            ->update(
                [
                    'show_sale' => $stat
                ]
            );
        if ($data) {
            messageSuccess('เปลี่ยนสถานะการแสดงสำเร็จ');
        } else {
            messageError('เปลี่ยนสถานะการแสดงล้มเหลว');

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);exit;

        $name_main = $_REQUEST['name_main'];
        $price_main = $_REQUEST['price_main'];
        $link = $_REQUEST['link'];
        $tel_main = $_REQUEST['tel_main'];
        $address_main = $_REQUEST['address_main'];
        $detail_main = $_REQUEST['detail_main'];
        $time_main = $_REQUEST['time_main'];
        $link_vdo = $_REQUEST['link_vdo'];
        $stat_show = @$_REQUEST['stat_show'];
        if ($stat_show != '') {
            $stat_show = 'y';
        } else {
            $stat_show = 'n';
        }
        $typeVoucher = @$request->type_voucher;
        $mainVoucher = new MainVoucherModel();
        $mainVoucher->code_type = @implode(',', $typeVoucher);
        $mainVoucher->name_main = $name_main;
        $mainVoucher->detail_main = $detail_main;
        $mainVoucher->link_main = $link;
        $mainVoucher->time_main = $time_main;
        $mainVoucher->address_main = $address_main;
        $mainVoucher->tel_main = $tel_main;
        $mainVoucher->price_main = $price_main;
        $mainVoucher->stat_show = $stat_show;
        $mainVoucher->link_vdo = $link_vdo;
        $mainVoucher->save();
        $id = $mainVoucher->id_main;
        if (isset(request()->files) && count(request()->files) > 0) {

            $images = insertMultipleImage($request, 'main', 'files');
            if (count($images) > 0) {
                foreach ($images as $img) {
                    $arrData = array(
                        'relationId' => $id,
                        'relationTable' => 'main',
                        'name' => $img,
                    );
                    DB::table('system_file')->insert($arrData);

                }
            }
        } else {
            $images = '';
        }
        return redirect(url('/backoffice/main_voucher'))->with('success', 'เพิ่มข้อมูล Main voucher เรียบร้อยแล้ว');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['data'] = MainVoucherModel::where('id_main', $id)->first();
        return view('backoffice.main_voucher.show', $data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = MainVoucherModel::where('id_main', $id)->first();
        $album = SystemFileModel::where('relationId', $id)->where('relationTable', 'main')->orderBy('sort_img', 'ASC')->get();
        $typeVoucher = TypeVoucher::query()->get();
        return view('backoffice.main_voucher.edit', compact('data', 'album', 'typeVoucher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        dd($request);exit;
        $typeVoucher = @$request->type_voucher;
        if ($request->deleteImage != '') {
            $idImg = $request->deleteImage;
            $delImage = SystemFileModel::where('id', $idImg)->first();
            Storage::delete('main/' . $delImage->name);
            DB::table('system_file')->where('id', $idImg)->delete();
//            alertSuccess('ลบรูปภาพเรียบร้อย', 'success', '../../main_voucher/' . $id . '/edit');
            return redirect(url('/backoffice/main_voucher/' . $id . '/edit'));

        } else {
            $name_main = $_REQUEST['name_main'];
            $price_main = $_REQUEST['price_main'];
            $link = $_REQUEST['link'];
            $tel_main = $_REQUEST['tel_main'];
            $address_main = $_REQUEST['address_main'];
            $detail_main = $_REQUEST['detail_main'];
            $time_main = $_REQUEST['time_main'];
            $link_vdo = $_REQUEST['link_vdo'];
            $stat_show = @$_REQUEST['stat_show'];
            if ($stat_show != '') {
                $stat_show = 'y';
            } else {
                $stat_show = 'n';
            }
            if (isset(request()->files) && count(request()->files) > 0) {
                $images = insertMultipleImage($request, 'main', 'files');
                foreach ($images as $img) {
                    $arrData = array(
                        'relationId' => $id,
                        'relationTable' => 'main',
                        'name' => $img,
                    );
                    DB::table('system_file')->insert($arrData);

                }
            }
            $data = MainVoucherModel::where('id_main', $id)->update(
                [
                    'code_type' => @implode(',', $typeVoucher),
                    'name_main' => $name_main,
                    'detail_main' => $detail_main,
                    'link_main' => $link,
                    'time_main' => $time_main,
                    'price_main' => $price_main,
                    'tel_main' => $tel_main,
                    'address_main' => $address_main,
                    'stat_show' => $stat_show,
                    'link_vdo' => $link_vdo,
                ]);
            if ($data) {
//                return redirect(url('/backoffice/main_voucher'))->with('success', 'แก้ไขข้อมูล Main voucher เรียบร้อยแล้ว');
                return redirect(url('/backoffice/main_voucher/' . $id . '/edit'))->with('success', 'แก้ไขข้อมูล Main voucher เรียบร้อยแล้ว');
            } else {
                messageError('!Error Update');
            }
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        MainVoucherModel::destroy($id);
//        messageSuccess('Delete success');

//        $rowB = MainVoucherModel::where('id_main', $id)->first();
        $row = SystemFileModel::where('relationId', $id)->where('relationTable', 'main')->get();
        foreach ($row as $val) {
//            echo $val->name;
            Storage::delete('main/' . $val->name);
        }
        SystemFileModel::where('relationId', $id)->where('relationTable', 'main')->delete();
        SelectVoucherModel::where('main_join', $id)->delete();
        $getVoucher = VoucherModel::query()->select('voucher_id')->where('relation_mainid', $id)->get();
        $idV = [];
        foreach ($getVoucher as $v) {
            $idV[] = $v->voucher_id;
        }
        $rows = SystemFileModel::whereIn('relationId', $idV)->where('relationTable', 'voucher')->get();
        foreach ($rows as $val) {
//            echo $val->name;
            Storage::delete('voucher/' . $val->name);
        }
        VoucherModel::query()->where('relation_mainid', $id)->delete();
        MainVoucherModel::destroy($id);
        messageSuccess('Delete success');

//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable(Request $request)
    {
        $type = $request->type;
        $data = MainVoucherModel::query();
        if ($type != '') {
            $data = $data->where('code_type', 'LIKE', '%' . $type . '%');
        }
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('link', function ($data) {
                $detail = '<a href="' . $data->link_main . '" target="_blank">' . $data->link_main . '</a>';
                return $detail;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_main, true, true, true, 'backoffice/main_voucher');
                return $Manage;
            })
            ->addColumn('Sale', function ($data) {
                $option = '<center><label class="switch">
                                    <input type="checkbox" onchange="changeShowSale($(this))" name="show" value="' . $data->id_main . '" ' . ($data->show_sale == 'y' ? 'checked' : '') . ' id="switch">
                                    <span class="slider round"></span>
                                </label></center>';
                return $option;
            })
            ->rawColumns(['No', 'Sale', 'link', 'Manage'])
            ->make(true);
    }
}
