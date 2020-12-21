<?php

namespace App\Http\Controllers\admin;

use App\Model\admin\FacilitiesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\MainVoucherModel;
use App\Model\admin\VoucherModel;
use Validator;
use DB;
use Datatables;
use Storage;
use Excel;
use App\Http\Controllers\PHPExcel_Style_Alignment;
use Carbon\Carbon;
use Session;
use App\Model\admin\SystemFileModel;
use App\Model\admin\SelectVoucherModel;
use App\Http\Requests\Checkvoucher;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getMain = @$request->main;
        $main = MainVoucherModel::query()->orderBy('name_main', 'ASC')->get();
        $option = '<option value="">ทั้งหมด</option>';
        foreach ($main AS $v) {
            $option .= '<option value="' . $v->id_main . '" ' . ($getMain == $v->id_main ? 'selected' : '') . '>' . $v->name_main . '</option>';

        }
        return view('backoffice.voucher.index', compact('option', 'getMain'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
//        $users = DB::table('users')->distinct()->get();
        $main = @$request->main;
        $data = MainVoucherModel::all();
        $facilities = FacilitiesModel::all();
        return view('backoffice.voucher.create', compact('data', 'facilities', 'main'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(request()->main_voucher);
//        [] => 1 [blog] => 1 [facilities] => Array ( [0] => 1 [1] => 2 [2] => 3 ) [name_voucher] => 11 [qty_rest] => 11 [qty_night] => 11 [qty_voucher] => 111 [price_agent] => 1111 [sale] => 111 [price] => 1000 [title_voucher] => 11 [link] => 11 [time_open] => 10/15/2018 00:00 - 10/31/2018 23:59 [term_voucher] =>
//11
        $typeVoucher = (@$request->typeVoucher == '' ? 'in' : $request->typeVoucher);
        $tel_voucher_contact = $request->tel_voucher_contact;
        $link_voucher_contact = $request->link_voucher_contact;
        $qty_sale = @request()->qty_sale;
        $sale_auto = request()->sale_auto;
        if (@$sale_auto == 1) {
            $s_auto = 'y';
            $type_auto = 0;
        } else {
            $s_auto = 'n';
            $type_auto = $qty_sale;

        }
        $time = explode('-', request()->time_open);
        $time_1 = date('Y-m-d H:i:s', strtotime($time[0]));
        $time_2 = date('Y-m-d H:i:s', strtotime($time[1]));
        $main_voucher = request()->main_voucher;
        $facilities = @request()->facilities;
        $name_voucher = request()->name_voucher;
        $qty_rest = request()->qty_rest;
        $qty_voucher = request()->qty_voucher;
        $price_agent = request()->price_agent;
        $sale = request()->sale;
        $price = request()->price;
        $title_voucher = request()->title_voucher;
        $term_voucher = request()->term_voucher;
        $link = request()->link;
        $detail_extra = request()->detail_extra;
        $name_extra = request()->name_extra;
        $qty_night = request()->qty_night;
        if (request()->fileToUpload != '') {
            $fileName = insertSingleImage($request, 'voucher');
        } else {
            $fileName = '';
        }
        $status_countdown = (@$request->status_countdown != '' ? @$request->status_countdown : 'sale');

        $voucher = new VoucherModel;
        $voucher->relation_mainid = $main_voucher;
        $voucher->name_voucher = $name_voucher;
        $voucher->relation_facilityid = (@count($facilities) > 0 ? implode(',', $facilities) : '');
        $voucher->stat_sale = $s_auto;
        $voucher->detail_stat_sale = $type_auto;
        $voucher->qty_customer = $qty_rest;
        $voucher->qty_night = $qty_night;
        $voucher->date_open = $time_1;
        $voucher->date_close = $time_2;
        $voucher->status_countdown = $status_countdown;
        $voucher->qty_voucher = $qty_voucher;
        $voucher->price_agent = $price_agent;
        $voucher->sale = $sale;
        $voucher->price_sale = $price;
        $voucher->title_voucher = $title_voucher;
        $voucher->term_voucher = $term_voucher;
        $voucher->img_show = $fileName;
        $voucher->name_extra = $name_extra;
        $voucher->detail_extra = $detail_extra;
        $voucher->type_voucher = $typeVoucher;
        $voucher->link_voucher_contact = $link_voucher_contact;
        $voucher->tel_voucher_contact = $tel_voucher_contact;
        $voucher->save();
        $id = $voucher->voucher_id;

        if ($_FILES['files']['name'][0] != "" && count($_FILES['files']['name']) > 0) {
            $images = insertMultipleImage($request, 'voucher', 'files');
            if (count($images) > 0) {
                foreach ($images As $img) {
                    $arrData = array(
                        'relationId' => $id,
                        'relationTable' => 'voucher',
                        'name' => $img,
                    );
                    DB::table('system_file')->insert($arrData);

                }
            }
        }


        return redirect(url('/backoffice/voucher?main=' . $main_voucher))->with('success', 'บันทึกข้อมูล Voucher เรียบร้อยแล้ว');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = VoucherModel::query()->where('voucher_id', $id)->first();
        $dataSuccess = VoucherModel::query()
            ->join("tb_order_detail AS detail", "tb_voucher.voucher_id", 'detail.voucher_id')
            ->join("tb_order AS order", "detail.order_id", 'order.id')
            ->join("tb_member AS member", "order.user_id", 'member.id_member')
            ->where('tb_voucher.voucher_id', $id)->where('status_order', '000')->orderByDesc('order.id')->get();

        $dataPending = VoucherModel::query()
            ->join("tb_order_detail AS detail", "tb_voucher.voucher_id", 'detail.voucher_id')
            ->join("tb_order AS order", "detail.order_id", 'order.id')
            ->join("tb_member AS member", "order.user_id", 'member.id_member')
            ->where('tb_voucher.voucher_id', $id)->where('status_order', '001')->orderByDesc('order.id')->get();

        $dataCancel = VoucherModel::query()
            ->join("tb_order_detail AS detail", "tb_voucher.voucher_id", 'detail.voucher_id')
            ->join("tb_order AS order", "detail.order_id", 'order.id')
            ->join("tb_member AS member", "order.user_id", 'member.id_member')
            ->where('tb_voucher.voucher_id', $id)->where('status_order', '!=', '001')->where('status_order', '!=', '000')->orderByDesc('order.id')->get();
        return view('backoffice.voucher.show', compact('dataCancel', 'dataPending', 'dataSuccess', 'data'));

    }

    public function changeSale(Request $request, $id)
    {
        $stat = $request->stat;

        $data = VoucherModel::where('voucher_id', $id)
            ->update(
                [
                    'show_sale_v' => $stat
                ]
            );
        if ($data) {
            messageSuccess('เปลี่ยนสถานะการแสดงสำเร็จ');
        } else {
            messageError('เปลี่ยนสถานะการแสดงล้มเหลว');

        }

    }

    public function changeStatusVoucher(Request $request, $id)
    {
        $stat = $request->stat;

        $data = VoucherModel::where('voucher_id', $id)
            ->update(
                [
                    'status_voucher' => $stat
                ]
            );
        if ($data) {
            messageSuccess('เปลี่ยนสถานะการแสดงสำเร็จ');
        } else {
            messageError('เปลี่ยนสถานะการแสดงล้มเหลว');

        }

    }

    public function changeExipired(Request $request, $id)
    {
        $stat = $request->stat;

        $data = VoucherModel::where('voucher_id', $id)
            ->update(
                [
                    'expired' => $stat
                ]
            );
        if ($data) {
            messageSuccess('เปลี่ยนสถานะการแสดงสำเร็จ');
        } else {
            messageError('เปลี่ยนสถานะการแสดงล้มเหลว');

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $main = MainVoucherModel::all();
        $facilities = FacilitiesModel::all();
        $data = VoucherModel::where('voucher_id', $id)->first();
        $file = SystemFileModel::where('relationId', $id)->where('relationTable', 'voucher')->first();
        $album = SystemFileModel::where('relationId', $id)->where('relationTable', 'voucher')->orderBy('sort_img', 'ASC')->get();
//dd(str_replace(' ','T',$data->date_end_post));
        return view('backoffice.voucher.edit', compact('main', 'data', 'facilities', 'file', 'album'));
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
        $typeVoucher = (@$request->typeVoucher == '' ? 'in' : $request->typeVoucher);
        $tel_voucher_contact = $request->tel_voucher_contact;
        $link_voucher_contact = $request->link_voucher_contact;
        if ($request->deleteImage != '') {
            $idImg = $request->deleteImage;
            $delImage = SystemFileModel::where('id', $idImg)->first();
            Storage::delete('voucher/' . $delImage->name);
            DB::table('system_file')->where('id', $idImg)->delete();
//            alertSuccess('ลบรูปภาพเรียบร้อย', 'success', '../../voucher/' . $id . '/edit');
            return redirect(url('/backoffice/voucher/' . $id . '/edit'));

        } else {
            $qty_sale = @request()->qty_sale;
            $sale_auto = request()->sale_auto;
            if (@$sale_auto == 1) {
                $s_auto = 'y';
                $type_auto = 0;
            } else {
                $s_auto = 'n';
                $type_auto = $qty_sale;

            }
            if (request()->fileToUpload != '') {
                $fileName = insertSingleImage($request, 'voucher');
            } else {
                $getImage = VoucherModel::where('voucher_id', $id)->first();
                $fileName = $getImage->img_show;
            }
            $time = explode('-', request()->time_open);
            $time_1 = date('Y-m-d H:i:s', strtotime($time[0]));
            $time_2 = date('Y-m-d H:i:s', strtotime($time[1]));
            $main_voucher = request()->main_voucher;

            $facilities = request()->facilities;
            $name_voucher = request()->name_voucher;
            $qty_rest = request()->qty_rest;
            $qty_voucher = request()->qty_voucher;
            $price_agent = request()->price_agent;
            $sale = request()->sale;
            $price = request()->price;
            $title_voucher = request()->title_voucher;
            $term_voucher = request()->term_voucher;
            $qty_night = request()->qty_night;
            $detail_extra = request()->detail_extra;
            $name_extra = request()->name_extra;
            if ($_FILES['files']['name'][0] != "" && count($_FILES['files']['name']) > 0) {
                $images = insertMultipleImage($request, 'voucher', 'files');
                if (count($images) > 0) {
                    foreach ($images As $img) {
                        $arrData = array(
                            'relationId' => $id,
                            'relationTable' => 'voucher',
                            'name' => $img,
                        );
                        DB::table('system_file')->insert($arrData);

                    }
                }
            }

            $status_countdown = (@$request->status_countdown != '' ? @$request->status_countdown : 'sale');

            $data = VoucherModel::where('voucher_id', $id)
                ->update(
                    [
                        'relation_mainid' => $main_voucher,
                        'name_voucher' => $name_voucher,
                        'relation_facilityid' => (@count($facilities) > 0 ? implode(',', $facilities) : ''),
                        'stat_sale' => $s_auto,
                        'detail_stat_sale' => $type_auto,
                        'qty_customer' => $qty_rest,
                        'date_open' => $time_1,
                        'date_close' => $time_2,
                        'status_countdown' => $status_countdown,
                        'qty_voucher' => $qty_voucher,
                        'qty_night' => $qty_night,
                        'price_agent' => $price_agent,
                        'sale' => $sale,
                        'price_sale' => $price,
                        'title_voucher' => $title_voucher,
                        'term_voucher' => $term_voucher,
                        'img_show' => $fileName,
                        'name_extra' => $name_extra,
                        'detail_extra' => $detail_extra,
                        'type_voucher' => $typeVoucher,
                        'link_voucher_contact' => $link_voucher_contact,
                        'tel_voucher_contact' => $tel_voucher_contact
                    ]
                );
            if ($data) {
                SelectVoucherModel::where('voucher_id_join', $id)
                    ->update(
                        [
                            'main_join' => $main_voucher
                        ]
                    );
                return redirect(url('/backoffice/voucher/'. $id.'/edit?main=' . $main_voucher))->with('success', 'แก้ไขข้อมูล Voucher เรียบร้อยแล้ว');
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
        $sorting_voucher = DB::table('sorting_voucher')->where('relation_voucher', '=', $id)->delete();

        $row = SystemFileModel::where('relationId', $id)->where('relationTable', 'voucher')->get();
        foreach ($row AS $val) {
//            echo $val->name;
            Storage::delete('voucher/' . $val->name);
        }
        SystemFileModel::where('relationId', $id)->where('relationTable', 'voucher')->delete();
//        exit;

        VoucherModel::destroy($id);
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }


    //index
    public function queryDatatable(Request $request)
    {

        $main = $request->main;
        $data = VoucherModel::query()
            ->leftJoin('main_voucher', 'tb_voucher.relation_mainid', 'main_voucher.id_main')
            ->leftJoin('select_voucher AS s', 'main_voucher.id_main', 's.main_join');
        if ($main != '') {
            $data = $data->where('relation_mainid', $main);
        }
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('Manage', function ($data) {
//                $Manage = buttonManageData($data->voucher_id, true, true, true, 'backoffice/voucher');
                $Manage = '<center>
<a title="View" href="#" onclick="viewShow(' . $data->voucher_id . ')" class="btn btn-flat btn-outline-primary ">
<i class="far fa-eye"></i>
</a>
 <a title="Edit" href="https://www.sneaksdeal.com/backoffice/voucher/' . $data->voucher_id . '/edit?main=' . $data->relation_mainid . '" class="btn btn-flat btn-outline-warning">
 <i class="far fa-edit"></i>
 </a>
 <a title="Delete" href="#" onclick="deleteData(' . $data->voucher_id . ')" class="btn btn-flat btn-outline-danger">
 <i class="far fa-trash-alt"></i>
 </a></center>';
                return $Manage;
            })
            ->addColumn('option', function ($data) {
                $option = '<center><label class="switch">
                                    <input type="checkbox" onchange="changeShowFilter($(this))" name="show" value="' . $data->voucher_id . '" ' . ($data->expired == 'y' ? 'checked' : '') . ' id="switch">
                                    <span class="slider round"></span>
                                </label></center>';
                return $option;
            })
            ->addColumn('Type', function ($data) {
                $val = '';
                if ($data->type_voucher == 'in') {
                    $val = '<label class="label label-success">ขายภายในเว็บไซต์</label>';
                } else {
                    $val = '<label class="label label-warning">เสนอขายนอกเว็บไซต์</label>';

                }
                return $val;
            })
            ->addColumn('Sale', function ($data) {
                $option = '<center><label class="switch">
                                    <input type="checkbox" onchange="changeShowSale($(this))" name="show" value="' . $data->voucher_id . '" ' . ($data->show_sale_v == 'y' ? 'checked' : '') . ' id="switch">
                                    <span class="slider round"></span>
                                </label></center>';
                return $option;
            })
            ->addColumn('Show', function ($data) {
                if($data->voucher_id_join != $data->voucher_id) {
                    $option = '<center><label class="switch">
                                    <input type="checkbox" onchange="changeShowVoucher($(this))" name="show_voucher" value="' . $data->voucher_id . '" ' . ($data->status_voucher == 'show' ? 'checked' : '') . ' >
                                    <span class="slider round"></span>
                                </label></center>';
                }else{
//                    $option = '<label class="label label-warning">ไม่สามารถซ่อนได้</label>' ;
                    $option = '<label class="label label-warning">ไม่สามารถซ่อนได้</label>' ;
                }
                return $option;
            })
            ->rawColumns(['No', 'Sale', 'option', 'Manage', 'Type', 'Show'])
            ->make(true);
    }

    public function viewSorting()
    {
        $voucher = VoucherModel::query()->get();
        $sort = DB::table('sorting_voucher')->leftJoin('tb_voucher AS v', 'sorting_voucher.relation_voucher', '=', 'v.voucher_id')->orderBy('sorting_voucher.sort_view', 'ASC')->get();
        return view('backoffice.voucher.sorting', compact('voucher', 'sort'));
    }

    public function addSorting(Request $request)
    {

        $insert = DB::table('sorting_voucher')->insert(
            [
                'relation_voucher' => $request->voucher,
                'sort_view' => 999
            ]
        );
        if ($insert) {
            $sort = DB::table('sorting_voucher')->leftJoin('tb_voucher AS v', 'sorting_voucher.relation_voucher', '=', 'v.voucher_id')
                ->orderBy('sorting_voucher.sort_view', 'ASC')->get();

            return $sort;
        }
    }

    public function saveSorting(Request $request)
    {
        foreach ($request->voucher AS $key => $val) {
            $no = $key + 1;
            DB::table('sorting_voucher')->where('relation_voucher', $val)->update(
                [
                    'sort_view' => $no
                ]
            );
        }
        alertSuccess('จัดเรียงสำเร็จ', 'success', url('backoffice/voucher/sort'));
    }

    public function deleteSorting($id)
    {
        DB::table('sorting_voucher')->where('id_sorting', $id)->delete();
        $sort = DB::table('sorting_voucher')->leftJoin('tb_voucher AS v', 'sorting_voucher.relation_voucher', '=', 'v.voucher_id')->orderBy('sorting_voucher.sort_view', 'DESC')->get();

        return $sort;
    }

    public function showPDF()
    {
        $vouchers = DB::table('tb_voucher')
            ->leftjoin('main_voucher', 'tb_voucher.relation_mainid', '=', 'main_voucher.id_main')
            ->get();

        Excel::create(Carbon::now()->format('YmdHis') . '_Process_data', function ($excel) use ($vouchers) {


            $excel->setTitle('Voucher List');
            $excel->setCreator('Me')->setCompany('Our Code World');
            $excel->setDescription('A demonstration to change the file properties');


            $excel->sheet('Sheet 1', function ($sheet) use ($vouchers) {

                $sheet->setOrientation('portrait');

                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1:H1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->setCellValueByColumnAndRow(0, 1, "รายงาน Voucher");
                $sheet->cell('A1:H2', function ($cell) use ($vouchers) {
                    $cell->setFont(array(
                        'bold' => true,
                        'size' => 10
                    ));
                });
                $sheet->row(2, array(
                    '#',
                    'ชื่อ Voucher',
                    'โรงแรม',
                    'จำนวน',
                    'ราคาก่อนลด',
                    'ราคาลด',
                    'ราคาขาย',
                    'สถานะการขาย',
                ));

                $sheet->getStyle('A2:H2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));

                foreach ($vouchers as $key => $value) {
                    if ($value->stat_sale == 'y') {
                        $stat_sale = 'เปิดขาย';
                    } else {
                        $stat_sale = 'ยังไม่เปิดขาย';
                    }
                    $sheet->row($key + 3, array(
                        $key + 1,
                        $value->name_voucher,
                        $value->name_main,
                        $value->qty_voucher,
                        $value->price_agent,
                        $value->sale,
                        $value->price_sale,
                        $stat_sale,
                    ));
                }
            });

        })->download('xlsx');
    }

}
