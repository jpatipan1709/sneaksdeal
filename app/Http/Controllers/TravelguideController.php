<?php

namespace App\Http\Controllers\admin;

use App\Model\admin\TypeBlogModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\admin\BlogModel;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\SystemFileModel;
use DB;
use Datatables;
use Storage;
class TravelguideController extends Controller
{
    public function index(){

        $blogBanner = BlogModel::query()->leftJoin('select_voucher as c', 'tb_blog.id_blog', 'c.blog_id_join')
            ->leftJoin('tb_voucher as vo', 'c.voucher_id_join', 'vo.voucher_id')->orderBy('c.sort_by_view','DESC')->limit(1)->first();
        $blog = BlogModel::where("id_blog",'!=',$blogBanner->id_blog)->get();
        return view('pages.travelguide',compact('blogBanner','blog'));

    }
    public function detail($id){
        $blog = BlogModel::where('id_blog',$id)->first();
        $file = SystemFileModel::where('relationId',$id)->where('relationTable','blog')->orderBy('sort_img','asc')->get();
        return view('pages.travelblogdetail',compact('blog','file'));

    }
}