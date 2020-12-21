<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\admin\TypeBlogModel;
use App\Http\Controllers\Controller;
use App\Model\admin\BlogModel;
use App\Model\admin\SelectVoucherModel;
use App\Model\admin\SystemFileModel;
use App\Joinus;
use App\Http\Requests\Checkjoinus;
use DB;
use Datatables;
use Storage;
use Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mail;
class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $blogBanner = BlogModel::query()->where('show_index','show')->first();

        $travel_guides = DB::table('travel_guides')
            ->where('tg_status', '=', '1')
            ->get();
        return view('pages.travelguide', compact('blogBanner', 'travel_guides'));

    }

    public function detail($id)
    {
      
        $blog = BlogModel::where('id_blog', $id)->first();

        if (@$blog->id_blog != '') {

            if (empty(Session::get('view_last'))) {
                $countTotal = 0 ;
                $time = time();
                session(['view_last' => $time]);
                DB::table('featured_total')->insert(
                    [
                        'blog_feat' => $id,
                        'time_stamp' => $time
                    ]
                );
            } else {
                $checkFeat = DB::table('featured_total')
                    ->where('blog_feat', $id)
                    ->where('time_stamp', Session::get('view_last'))
                    ->get();
                $countTotal = count($checkFeat);
                if (count($checkFeat) == 0) {
                    DB::table('featured_total')
                        ->insert(
                            [
                                'blog_feat' => $id,
                                'time_stamp' => Session::get('view_last')
                            ]
                        );
                }else{
                    DB::table('featured_total')->where('blog_feat',$id)
                        ->where( 'time_stamp', Session::get('view_last'))
                        ->update(
                            [
                                'created_at' => date('Y-m-d H:i:s'),
                            ]
                        );
                }
            }
            $checkView = DB::table('total_view')->where('blog_id_view', '=', $id)->first();
            if (@$checkView->blog_id_view == '') {
                DB::table('total_view')->insert(
                    [
                        'blog_id_view' => $id,
                        'qty' => 1
                    ]
                );
            } else {
                DB::table('total_view')->where('blog_id_view', '=', $id)->update(
                    [
                        'qty' => $checkView->qty + 1
                    ]
                );
            }


            $viewTotal = DB::table('total_view')
                ->leftJoin('tb_blog', 'total_view.blog_id_view', '=', 'tb_blog.id_blog')
                ->orderBy('total_view.qty', 'DESC')->limit('5')
                ->get();
            $file = SystemFileModel::where('relationId', $id)->where('relationTable', 'blog')->offset(1)->limit(100)->orderBy('sort_img','asc')->get();

            return view('pages.travelblogdetail', compact('blog', 'file', 'viewTotal', 'id','countTotal'));
        } else {
            return redirect('travelguide');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showtag($id){
     
        $blogs = DB::table('tb_blog')
                ->where('type_blog','like','%'.$id.'%')
                ->where('deleted_at','=',null)
                ->get();

        $blog_tag = DB::table('type_blog')
                ->where('type_blogid','=',$id)
                ->first();
     
        $data = array(
           'blogs' => $blogs,
           'blog_tag' => $blog_tag 
        );
        return view('pages.tagtravel',$data);
    }

    public function  joinus(){
        $joinus_content = DB::table('joinus_content')->first();

        $data = array(
            'joinus_content' => $joinus_content,
         );

        return view('pages.joinus',$data);
    }

    public function  addjoin(Checkjoinus $request){
        $joinus = new Joinus();
        $joinus->ju_name = $request->name;
        $joinus->ju_hotel = $request->hotel;
        $joinus->ju_tel = $request->tel;
        $joinus->ju_email = $request->mail;
        $joinus->ju_content = $request->comment;
        $joinus->save();
        $message = '<html>';
        $message .= '<head>';
        $message .= '<link href="https://fonts.googleapis.com/css?family=Prompt:400,500,700|Roboto:400,500,700" rel="stylesheet">';
        $message .= '</head>';
        $message .= '	<body>';
        $message .= '		<style>';
        $message .= '			body {';
        $message .= '			    font-family: "Roboto", "Prompt", sans-serif;';
        $message .= '			    color: #000;';
        $message .= '			    text-align: center;';
        $message .= '			}';
        $message .= '			@media print {';
        $message .= '			  body, page {';
        $message .= '			    margin: 0;';
        $message .= '			    box-shadow: 0;';
        $message .= '			  }';
        $message .= '			    .breaknewpage{';
        $message .= '			    page-break-after: always;';
        $message .= '			        }';
        $message .= '			}';
        $message .= '		</style>';
        
        $message .= "	<div class=\"page_container\" style=\"padding: 15px 40px; max-width: 800px; width: 100%; background: #fbdc07; font-family: 'Roboto', 'Prompt', sans-serif;
            color: #000;\">";
        $message .= "			    <img class=\"logo\" src=\"https://www.sneaksdeal.com/img/sneakoutlogo.jpg\"  style=\"margin: 0 auto 25px auto; width: 120px; height: auto; display: block;\">";
        $message .= "			    <div style=\"font-size: 22px; text-align: center; font-weight: 500; margin-bottom: 10px;\">ขอขอบคุณ ทาง $request->hotel  ที่สนใจร่วมเป็นส่วนหนึ่งกับเรา<br>ทางเราจะติดต่อกลับ ให้เร็วที่สุด ขอบคุณค่ะ</div>";
        $message .= "			    <div style=\"display:block; text-align:center; margin-top:15px; margin-bottom:15px;\">
                    <a href=\"tel:+6667701732\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                        <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/icon02.png\">
                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">08-677-01732</span>
                    </a>
                    <a target=\"_blank\" href=\"https://www.facebook.com/sneakoutclub/\" style=\"color:#000; text-decoration:none; display:inline-block; width:120px; vertical-align:top;\">
                        <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/social_icon_facebook.png\">
                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">Sneaksdeal</span>
                    </a>
                    <a target=\"_blank\" href=\"#\" style=\"color:#000; text-decoration:none; display:inline-block; width:150px; vertical-align:top;\">
                        <img style=\"display:block; margin:0 auto 10px auto;\" src=\"https://www.sneaksdeal.com/img/joinus/icon03.png\">
                        <span style=\"display:block; font-size:14px; font-weight:500; color:#000;\">sneakoutclub@gmail.com</span>
                    </a>
                 
                </div>";
        $message .= "			    <div style=\"border-bottom: 1px dashed #ccc; margin-top: 15px; margin-bottom: 25px;\"></div>";
        $message .= "			    <div style=\"font-size: 11px; text-align: center;\">";
        $message .= '			        <p>117 ถนนแฉล้มนิมิตร แขวงบางโคล่ เขตบางคอแหลม กรุงเทพ 10120</p>';
        $message .= '			        <p>เว็บไซต์ : https://www.sneaksdeal.com</p>';
        $message .= '			    </div>';
        $message .= '			</div>';
        $message .= '	</body>';
        $message .= '</html>';

        // echo $message;
        try {
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->IsHTML(true);
            $mail->SMTPDebug  = 0;                  
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->CharSet = "utf-8";
            $mail->SetFrom($request->email, $request->firstName.' '.$request->lastName); 
            $mail->Username = 'sneaksdeal2018@gmail.com';    //User Sent
            $mail->Password = 'sneaksdeal1234';       //Pass Sent
            $mail->From  = 'sneaksdeal2018@gmail.com';    //User
            $mail->FromName = "https://sneaksdeal.com";
            $mail->Subject  = "ขอขอบคุณที่สนใจร่วมเป็นส่วนหนึ่งกับเรา https://sneaksdeal.com ";
            $mail->Body  = $message;
            $mail->AddAddress($request->mail);
            $mail->set('X-Priority', '3');
            $mail->Send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

        return redirect(url('/joinus'))->with('success','ส่งข้อมูลติดต่อ ไปยัง https://www.sneaksdeal.com เรียบร้อยแล้ว!!');
    }
}
