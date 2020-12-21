<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Travelguide;
use App\Joinus;
use DB;
use Datatables;
use App\Http\Requests\Checktravel;
use App\Http\Requests\Checkupdatetravel;
use App\Http\Requests;
class TravelguideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backoffice.travelguide.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backoffice.travelguide.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Checktravel $request)
    {
 
            
            $travel = new Travelguide;
            $travel->tg_name = $request->travel_name;
            $travel->tg_status = 1;
            $travel->save();
            return redirect('backoffice/travelguidemanage')->with('success','บันทึกชื่อ Travel Guide Tag เรียบร้อย');
        
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $travel = DB::table('travel_guides')
                    ->where('tg_id','=',$id)
                    ->first();
        $blog = DB::table('tb_blog')
                    ->where('deleted_at','=',null)
                    ->get();
        $data = array(
            'travel' => $travel,
            'blog' => $blog
        );
        return view('backoffice.travelguide.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Checkupdatetravel $request, $id)
    {
        
        $blog_id = implode(",",$request->blogid);
  
        $travel = Travelguide::find($id);
        $travel->tg_name = $request->travel_name;
        $travel->tg_blog_id = $blog_id;
        $travel->tg_status = $request->tg_status;
        $travel->save();


        return redirect('backoffice/travelguidemanage')->with('success','เลือก blog  เรียบร้อย!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Travelguide::destroy($id);
        messageSuccess('Delete success');
    }

    public function queryDatatable()
    {
        $data = DB::table('travel_guides')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")
           
            ->addColumn('tg_status', function ($data) {
                if($data->tg_status == 0){
                    $tg_status = "ไม่ใช้งาน";
                }else{
                    $tg_status = "ใช้งาน";
                }
                return $tg_status;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->tg_id, false, true, true, 'backoffice/travelguidemanage');
                return $Manage;
            })
            ->rawColumns(['No',  'tg_status', 'Manage'])
            ->make(true);
    }

    public function joinus(){
        return view('backoffice.joinus.index');
    }

    public function queryDatatable2()
    {
        
        $data = DB::table('joinus')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('No', "")

            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->ju_id, true, false, true, 'backoffice/travelguidemanage');
                return $Manage;
            })
            ->rawColumns(['No', 'Manage'])
            ->make(true);
    }

    public function showjoinus($id){
        $joinus = DB::table('joinus')
                    ->where('ju_id','=',$id)
                    ->first();
        $data = array(
            'joinus' => $joinus
        );
        return view('backoffice.joinus.show',$data);
    }

    public function deljoinus($id){
        Joinus::destroy($id);
        messageSuccess('Delete success');
    }


    
    public function howitwork(){
        return view('backoffice.howitwork.index');
    }

    public function createhow(Request $request){

        // DB::table('tb_howitwork')->insert([
        //     ['hiw_detail' => $request->detail],
        // ]);
        
        $howitworks = DB::table('tb_howitwork')->first();
        // dd($howitworks);
        if($howitworks != null){

           
            DB::table('tb_howitwork')
            ->where('hiw_id', 1)
            ->update(['hiw_detail' => $request->detail]);

            // DB::table('tb_howitwork')->where('hiw_id', 1)->update([
            //     ['hiw_detail' => $request->detail],
            // ]);

            return redirect('/backoffice/howitwork')->with('success','แก้ไขข้อมูล How it work  เรียบร้อย!!');
        }else{
            // dd("FAIL");
            
           
            DB::table('tb_howitwork')->insert([
                ['hiw_detail' => $request->detail],
            ]);

            return redirect('/backoffice/howitwork')->with('success','บันทึกข้อมูล How it work  เรียบร้อย!!');
        }

        
    }
    public function howitworkindex(){
        $howitworks = DB::table('tb_howitwork')->first();
        $data = array(
            'howitworks' => $howitworks,
        );
        return view('pages.howitwork',$data);
    }
    
    public function showjoinindex(){
        // $howitworks = DB::table('tb_howitwork')->first();
        // $data = array(
        //     'howitworks' => $howitworks,
        // );
        return view('backoffice.joinus.formjoinus');
    }

    public function savejoinus(Request $request){
        // dd($request);
        if (request()->fileToUpload != '') {
             $fileName = insertSingleImage($request, 'joinus');
        } else {
             $fileName = '';
        }


        if (request()->fileToUpload2 != '') {
             $fileName2 = insertSingleImage2($request, 'joinus');
        } else {
             $fileName2 = '';
        }

        $joinuscontents = DB::table('joinus_content')->first();
        if($joinuscontents != null){
            if($fileName != '' && $fileName2 != ''){
                DB::table('joinus_content')
                ->where('juc_id', 1)
                ->update([
                    'juc_images1' => $fileName,
                    'juc_images2' => $fileName2,
                    'juc_text1' => $request->text1,
                    'juc_text2' => $request->text2,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->map,
                ]);
            }else if($fileName != ''){
                DB::table('joinus_content')
                ->where('juc_id', 1)
                ->update([
                    'juc_images1' => $fileName,
                    'juc_text1' => $request->text1,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->map,
                ]);
            }else if($fileName2 != ''){
                DB::table('joinus_content')
                ->where('juc_id', 1)
                ->update([
                    'juc_images2' => $fileName2,
                    'juc_text2' => $request->text2,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->map,
                ]);
            }else{
                DB::table('joinus_content')
                ->where('juc_id', 1)
                ->update([
                    'juc_text1' => $request->text1,
                    'juc_text2' => $request->text2,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->map,
                ]);
            }
            // DB::table('joinus_content')
            // ->where('juc_id', 1)
            // ->update([
            //     'juc_images1' => $fileName,
            //     'juc_images2' => $fileName2,
            //     'juc_text1' => $request->text1,
            //     'juc_text2' => $request->text2,
            //     'juc_content' => $request->detail,
            //     'juc_mail1' => $request->mail,
            //     'juc_tel1' => $request->phone,
            //     'juc_contact' => $request->contact_me,
            //     'juc_address' => $request->address,
            //     'juc_tel2' => $request->phone2,
            //     'juc_mail2' => $request->mail2,
            // ]);
            return redirect('/backoffice/showjoinindex')->with('success','บันทึกข้อมูล Join us  เรียบร้อย!!');
        }else{
            if($fileName != '' && $fileName2 != ''){
                DB::table('joinus_content')->insert([
                    [  
                    'juc_images1' => $fileName,
                    'juc_images2' => $fileName2,
                    'juc_text1' => $request->text1,
                    'juc_text2' => $request->text2,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->juc_map,
                    ],
                ]);
            }else if($fileName != ''){
                DB::table('joinus_content')->insert([
                    [  
                    'juc_images1' => $fileName,
                    'juc_text1' => $request->text1,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->juc_map,
                    ],
                ]);
            }else if($fileName2 != ''){
                DB::table('joinus_content')->insert([
                    [  
                    'juc_images2' => $fileName2,
                    'juc_text2' => $request->text2,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->juc_map,
                    ],
                ]);
            }else{
                DB::table('joinus_content')->insert([
                    [  
                    'juc_text1' => $request->text1,
                    'juc_text2' => $request->text2,
                    'juc_content' => $request->detail,
                    'juc_mail1' => $request->mail,
                    'juc_tel1' => $request->phone,
                    'juc_contact' => $request->contact_me,
                    'juc_address' => $request->address,
                    'juc_tel2' => $request->phone2,
                    'juc_mail2' => $request->mail2,
                    'juc_map' => $request->juc_map,
                    ],
                ]);
            }
            // DB::table('joinus_content')->insert([
            //     [  
            //     'juc_images1' => $fileName,
            //     'juc_images2' => $fileName2,
            //     'juc_text1' => $request->text1,
            //     'juc_text2' => $request->text2,
            //     'juc_content' => $request->detail,
            //     'juc_mail1' => $request->mail,
            //     'juc_tel1' => $request->phone,
            //     'juc_contact' => $request->contact_me,
            //     'juc_address' => $request->address,
            //     'juc_tel2' => $request->phone2,
            //     'juc_mail2' => $request->mail2,],
            // ]);
            return redirect('/backoffice/showjoinindex')->with('success','บันทึกข้อมูล Join us  เรียบร้อย!!');
        }
    }
}
