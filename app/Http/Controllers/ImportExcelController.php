<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\DiscountVoucher;
use App\Model\LogDiscountAlready;
use App\Discount;
use DB;

class ImportExcelController extends Controller
{

    public function importDiscount(Request $request)
    {
        insertSingleImage($request, 'discount');
        $path = $request->file('fileToUpload')->getRealPath();
        $getExcel = Excel::load($path)->get()->all();
        foreach ($getExcel AS $k => $val) {
            $i = 1;
            $arrayNew = [];
            foreach ($getExcel[$k] AS $kk => $column) {
                if ($i > 1) {
                    $arrayNew[] = $column;
                }
                $i++;
            }
            if (@count($arrayNew) > 0) {
                if (@$arrayNew[1] != '') {
                    $count1 = Discount::query()->select('discount_code')->where('discount_code', $arrayNew[1])->count();
                    $count2 = DiscountVoucher::query()->select('discount_code_multiple')->where('discount_code_multiple', $arrayNew[1])->count();

                    if ($count1 > 0 || $count2 > 0) {
                        $insert = new LogDiscountAlready();
                        $insert->code_up = $arrayNew[1];
                        $insert->code_name = $arrayNew[0];
                        $insert->save();
                    } else {
                        $check = Discount::query()->select('discount_name', 'discount_bath', 'discount_id')
                            ->where('discount_name', $arrayNew[0])->where('discount_bath', $arrayNew[5])->first();
                        if ($check) {
                            Discount::query()->where('discount_id',$check->discount_id)->update([
                                'discount_qty'=> DB::raw('discount_qty + 1')
                            ]);
                            $DiscountVoucher = new DiscountVoucher();
                            $DiscountVoucher->ref_discount_id = $check->discount_id;
                            $DiscountVoucher->discount_code_multiple = $arrayNew[1];
                            $DiscountVoucher->save();
                        } else {
                            $Discount = new Discount();
                            $Discount->discount_name = $arrayNew[0];
                            $Discount->discount_qty = 1;
                            $Discount->discount_min = ($arrayNew[4] == '' ? 0 : $arrayNew[4] != 0 ? $arrayNew[4] : 0);
                            $Discount->date_start = $arrayNew[2];
                            $Discount->date_end = $arrayNew[3];
                            $Discount->discount_bath = $arrayNew[5];
                            $Discount->discount_main = 0;
                            $Discount->type_discount = 'multiple_code';
                            $Discount->partner_name = $arrayNew[6];
                            $Discount->save();
                            $idDiscount = $Discount->discount_id;
                            $DiscountVoucher = new DiscountVoucher();
                            $DiscountVoucher->ref_discount_id = $idDiscount;
                            $DiscountVoucher->discount_code_multiple = $arrayNew[1];
                            $DiscountVoucher->save();
                        }
                    }
                }

            }
        }
        return redirect('backoffice/discount')->with('success', 'Excel Data Imported successfully.');

    }
}


function import(Request $request)
{
//    $this->validate($request, [
//        'select_file' => 'required|mimes:xls,xlsx'
//    ]);
//
//    $path = $request->file('select_file')->getRealPath();
//
//    $data = Excel::load($path)->get();
//
//    if ($data->count() > 0) {
//        foreach ($data->toArray() as $key => $value) {
//            dd($value);
//            foreach ($value as $row) {
//                $insert_data[] = array(
//                    'CustomerName' => $row['customer_name'],
//                    'Gender' => $row['gender'],
//                    'Address' => $row['address'],
//                    'City' => $row['city'],
//                    'PostalCode' => $row['postal_code'],
//                    'Country' => $row['country']
//                );
//            }
//        }
//
//        if (!empty($insert_data)) {
//            DB::table('tbl_customer')->insert($insert_data);
//        }
//    }
    return redirect('backoffice/discount')->with('success', 'Excel Data Imported successfully.');

}
