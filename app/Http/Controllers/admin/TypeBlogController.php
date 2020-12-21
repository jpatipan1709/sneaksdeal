<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\TypeBlogModel;
use Validator;
use DB;
use Datatables;
use Storage;
class TypeBlogController extends Controller
{
    public function index()
    {
        return view('backoffice.type_blog.index');
    }



    public function create()
    {
        return view('backoffice.type_blog.create');
    }



    public function store(Request $request)
    {
        $name = $_REQUEST['name_type'];
        $TypeBlog = TypeBlogModel::all();
        $validator = Validator::make($request->all(), [
            'name_type' => 'required|unique:type_blog,name_type',
        ]);

        if ($validator->fails()) {
            messageError('!Error please new key');

        } else {
            DB::table('type_blog')->insert([
                ['name_type' => $name ]
            ]);

            return redirect(route('type_blog.index'))->with('success','เพิ่มข้อมูล ประเภท Blog รียบร้อยแล้ว');

        }

    }

    public function show($id)
    {

       $data = TypeBlogModel::query()->whereIn('type_blogid', explode(',',$id))->get();
       $TEXT = NULL;
       $i = 1;
       foreach ($data  as $NAME){
         if($i != count($data)){
               $l = '|';

           }else{
               $l = '';

           }
           $TEXT .= $NAME->name_type.$l;
       $i++;}
        return $TEXT;
    }


    public function edit($id)
    {
        $data = TypeBlogModel::where('type_blogid', $id)->first();
        return view('backoffice.type_blog.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $name = $_REQUEST['name_type'];

        $data = DB::table('type_blog')->where('type_blogid', $id)->update([ 'name_type' =>$name ]);
            if ($data) {
                return redirect(route('type_blog.index'))->with('success','แก้ไขข้อมูล ประเภท Blog รียบร้อยแล้ว');
            } else {
                messageError('!Error Update');

            }



    }


    public function destroy($id)
    {
        DB::table('type_blog')->where('type_blogid', $id)->delete();
        messageSuccess('Delete success');
//        return view('backoffice.facilities.destroy');
    }



    public function queryDatatable()
    {
        $data = TypeBlogModel::query();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
           
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->type_blogid, false, true, true,'backoffice/type_blog');
                return $Manage;
            })

            ->rawColumns(['No', 'Manage'])
            ->make(true);
    }
}
