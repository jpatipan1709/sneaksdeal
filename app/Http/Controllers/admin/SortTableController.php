<?php


namespace App\Http\Controllers\admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Validator;

use DB;

use Datatables;

use Storage;
use Redirect;
//use App\Model\admin\BlogModel;
use App\Model\admin\SystemFileModel;


class SortTableController extends Controller

{

    public function sortAlbum(Request $request)
    {
        $i = 1;
        foreach (request()->imgSort AS $img) {
            SystemFileModel::where('id', $img)
                ->update(['sort_img' => $i]);
            $i++;
        }
        messageSuccess('Update sort image success');
//        return Redirect::back();

    }


}

