<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\BlogRequests;
use App\Model\admin\BlogModel;
use Validator;
use DB;
use Datatables;
use Storage;
use Redirect;
use App\Model\admin\SystemFileModel;
use App\Model\admin\TypeBlogModel;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function select_show()
    {
        $data = BlogModel::where('show_index', '=', 'show')->first();
        $blog = BlogModel::query()->get();

        return view('backoffice.blog.select_show', compact('data','blog'));
    }

    public function update_select(Request $request)
    {
        $blog = $request->blog;
        BlogModel::where('show_index', 'show')->update(
            [
                'show_index' => null,

            ]);
        BlogModel::where('id_blog', $blog)->update(
            [
                'show_index' => 'show',

            ]);
        return redirect(url('backoffice/blog/select_show'));
    }

    public function index()
    {

        return view('backoffice.blog.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = TypeBlogModel::all();

        return view('backoffice.blog.create', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequests $request)
    {
//dd($request);

        $detail = $_REQUEST['detail'];
        $title = $_REQUEST['title'];
        $name_blog = $_REQUEST['name_blog'];
        $time_open = $_REQUEST['time_open'];
        $tel = $_REQUEST['tel'];
        $price = $_REQUEST['price'];
        $type_blog = $_REQUEST['type_blog'];
        $address = $_REQUEST['address_blog'];

        if ($_FILES['files']['name'][0] != "" && count($_FILES['files']['name']) > 0) {
            $images = insertMultipleImage($request, 'blog', 'files');
        } else {
            $images[0] = '';
        }
        if (request()->fileToUpload != '') {
            $fileName = insertSingleImage($request, 'blog');
        } else {
            $fileName = '';
        }
        $blogModel = new BlogModel();
        $blogModel->name_blog = $name_blog;
        $blogModel->type_blog = implode('|', $type_blog);
        $blogModel->title_blog = $title;
        $blogModel->detail_blog = $detail;
        $blogModel->tel_blog = $tel;
        $blogModel->time_work = $time_open;
        $blogModel->price_blog = $price;
        $blogModel->address_blog = $address;
        $blogModel->banner_blog = $fileName;
        $blogModel->img_blog_index = $images[0];
        $blogModel->save();
        $id = $blogModel->id_blog;

        foreach ($images As $img) {
            $arrData = array(
                'relationId' => $id,
                'relationTable' => 'blog',
                'name' => $img,
            );

            DB::table('system_file')->insert($arrData);

        }

//        alertSuccess('บันทึกข้อมูลเรียบร้อย', 'success', '../blog');

        return redirect(url('backoffice/blog'))->with('success', 'เพิ่มข้อมูล Blog เรียบร้อยแล้ว');

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
        return view('backoffice.blog.show', $data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = TypeBlogModel::all();
        $data = BlogModel::where('id_blog', $id)->first();
        $album = SystemFileModel::where('relationId', $id)->where('relationTable', 'blog')->orderBy('sort_img', 'ASC')->get();
        return view('backoffice.blog.edit', compact('data', 'type', 'album'));
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
        // dd(request()->setImage);
//        print_r($_POST);exit;
        if (request()->deleteImage != '') {
            $idImg = request()->deleteImage;
            $delImage = SystemFileModel::where('id', $idImg)->first();
            Storage::delete('blog/' . $delImage->name);
            $data = DB::table('system_file')->where('id', $idImg)->delete();
            if ($data) {
                return Redirect::back();
            } else {
                return Redirect::back();
            }
        } else if (request()->setImage != '') {
            $idImg = request()->setImage;
            $delImage = SystemFileModel::where('id', $idImg)->first();
            $data = BlogModel::where('id_blog', $id)->update(
                [
                    'img_blog_index' => $delImage->name
                ]);

            if ($data) {
                // return Redirect::back();
                return Redirect::back()->with('success', 'แก้ไขข้อมูล Blog เรียบร้อยแล้ว');
            } else {
//                messageError('!Error Update');
                return Redirect::back();

            }
        } else {
          
            $detail = $_REQUEST['detail'];
            $title = $_REQUEST['title'];
            $name_blog = $_REQUEST['name_blog'];
            $time_open = $_REQUEST['time_open'];
            $tel = $_REQUEST['tel'];
            $price = $_REQUEST['price'];
            $address = $_REQUEST['address_blog'];
            $type_blog = $_REQUEST['type_blog'];
            $selectCheck = BlogModel::where('id_blog', $id)->first();
            if (request()->fileToUpload != '') {
                $fileName = insertSingleImage($request, 'blog');
            } else {
                $fileName = $selectCheck->banner_blog;
            }

            // dd($selectCheck->banner_blog);
            if ($_FILES['files']['name'][0] != "" && count($_FILES['files']['name']) > 0) {
                $images = insertMultipleImage($request, 'blog', 'files');
                foreach ($images As $img) {
                    $arrData = array(
                        'relationId' => $id,
                        'relationTable' => 'blog',
                        'name' => $img,
                    );
                    DB::table('system_file')->insert($arrData);

                }
            }
            $data = BlogModel::where('id_blog', $id)->update(
                [
                    'name_blog' => $name_blog,
                    'type_blog' => implode('|', $type_blog),
                    'title_blog' => $title,
                    'detail_blog' => $detail,
                    'tel_blog' => $tel,
                    'time_work' => $time_open,
                    'price_blog' => $price,
                    'address_blog' => $address,
                    'banner_blog' => $fileName

                ]);
            if ($data) {
                return redirect(url('backoffice/blog'))->with('success', 'แก้ไขข้อมูล Blog เรียบร้อยแล้ว');
            } else {
                return redirect(url('backoffice/blog'))->with('error', 'แก้ไขข้อมูล Blog ไม่สำเร็จ');


            }
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $total_view =  DB::table('total_view')->where('blog_id_view', '=',$id)->delete();

        $rowB = BlogModel::where('id_blog', $id)->first();
        $row = SystemFileModel::where('relationId', $id)->where('relationTable', 'blog')->get();
        foreach ($row AS $val) {
//            echo $val->name;
            Storage::delete('blog/' . $val->name);
        }
        SystemFileModel::where('relationId', $id)->where('relationTable', 'blog')->delete();
        
        Storage::delete('blog/' . $rowB->banner_blog);
        Storage::delete('blog/' . $rowB->img_blog_index);
        BlogModel::destroy($id);
        messageSuccess('Delete success');
    }


    //index
    public function queryDatatable()
    {
        $data = BlogModel::query();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
            ->addColumn('IMG', function ($data) {
                $img = '<img style="width:50px;height:50px" src="' . url("storage/blog/" . $data->banner_blog) . '">';
                return $img;
            })
            ->addColumn('type_blog_', function ($data) {
                $type = TypeBlogModel::query()->whereIn('type_blogid', explode('|', $data->type_blog))->get();

                $TEXT = NULL;
                $i = 1;
                foreach ($type as $NAME) {
                    if ($i != count($type)) {
                        $l = ' | ';
                    } else {
                        $l = '';
                    }
                    $TEXT .= $NAME->name_type . $l;
                    $i++;
                }
                return $TEXT;
//                return route('showTypeBlog',$data->type_blog);
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_blog, false, true, true, 'backoffice/blog');
                return $Manage;
            })
            ->rawColumns(['No', 'IMG', 'type_blog_', 'Manage'])
            ->make(true);
    }
}
