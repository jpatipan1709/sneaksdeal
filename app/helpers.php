<?php
if (!function_exists('btnBooking')) {
    function btnBooking($type)
    {
        if (preg_match('/S04/', $type)) {
//            $text = 'จองเลย';
            $text = 'จอง';
        } else if (preg_match('/S03/', $type)) {
            $text = 'จอง';
//            $text = 'จองเลย';
        } else if (preg_match('/S02/', $type)) {
            $text = 'จอง';
//            $text = 'จองเลย';
        } else {
//            $text = 'จองห้องพัก';
            $text = 'จอง';

        }
        return $text;
    }
}
if (!function_exists('postDate')) {
    function postDate($date)
    {
        $data = str_replace('/', '-', $date);
        $dateCreate = @date_create($data);
        $dateFormat = @date_format($dateCreate, 'Y-m-d');
        return $dateFormat;
    }
}
if (!function_exists('postDateTime')) {
    function postDateTime($date)
    {
        $data = str_replace('/', '-', $date);
        $dateCreate = @date_create($data);
        $dateFormat = @date_format($dateCreate, 'Y-m-d H:i:s');
        return $dateFormat;
    }
}
if (!function_exists('returnDate')) {
    function returnDate($date, $format)
    {
        $dateCreate = @date_create($date);
        $dateFormat = @date_format($dateCreate, $format);
        return $dateFormat;
    }
}


if (!function_exists('stepCartNew')) {
    function stepCartNew($page,$authStep3 = ''){
        $html = '';
        $click = '';
        if($authStep3 != ''){
            $click = 'onclick="window.location=\'/paymentending\'"';
        }
    if($page == 1){
            $html = '<div class="row " style="margin:0!important">
        <div class="col-12 d-none d-sm-block" >
        <ul class="progressbar-step">
          <li  onclick="window.location=\'/cart\'"  class="active ">ตะกร้าสินค้า</li>
          <li class="cursor-true" onclick="window.location=\'/paymentstarting\'">ตรวจสอบข้อมูล</li>
          <li '. $click. ' class="cursor-true">ช่องทางชำระเงิน</li>
          <li>สำเร็จ</li>
        </ul>
        </div></div>';
    }else if($page ==  2){
            $html = '<div class="row " style="margin:0!important">
        <div class="col-12 d-none d-sm-block">
        <ul class="progressbar-step">
          <li class="active cursor-true" onclick="window.location=\'/cart\'">ตะกร้าสินค้า</li>
          <li class="active " onclick="window.location=\'/paymentstarting\'">ตรวจสอบข้อมูล</li>
          <li class="cursor-true" ' . $click . ' >ช่องทางชำระเงิน</li>
          <li>สำเร็จ</li>
        </ul>
        </div></div>';
    }else if($page == 3){
            $html = '<div class="row " style="margin:0!important">
        <div class="col-12 d-none d-sm-block">
        <ul class="progressbar-step">
          <li class="active cursor-true" onclick="window.location=\'/cart\'">ตะกร้าสินค้า</li>
          <li class="active cursor-true" onclick="window.location=\'/paymentstarting\'">ตรวจสอบข้อมูล</li>
          <li  class="active ">ช่องทางชำระเงิน</li>
          <li>สำเร็จ</li>
        </ul>
        </div></div>';
    }else{
            $html = '<div class="row" style="margin:0!important">
        <div class="col-12 d-none d-sm-block">
        <ul class="progressbar-step">
          <li class="active ">ตะกร้าสินค้า</li>
          <li class="active " >ตรวจสอบข้อมูล</li>
          <li class="active ">ช่องทางชำระเงิน</li>
          <li  class="active">สำเร็จ</li>
        </ul>
        </div></div>';
    }
    return $html;

    }
}

if (!function_exists('stepCart')) {
    function stepCart($page,$authStep3 ='')
    {
        if ($page == 1) {
            $pc1 = URL::asset('img/cart/step01.png');
            $pc2 = URL::asset('img/cart/step2-new-gray.png');
            $pc3 = URL::asset('img/cart/step3-new-gray.png');
            $pc4 = URL::asset('img/cart/step4-new-gray.png');
            $mobile1 = URL::asset('img/cart/step21.png');
            $mobile2 = URL::asset('img/cart/step32.png');
            $mobile3 = URL::asset('img/cart/step33.png');
            $mobile4 = URL::asset('img/cart/no-step-4-gray.png');
        } else if ($page == 2) {
            $pc1 = URL::asset('img/cart/step11.png');
            $pc2 = URL::asset('img/cart/step2-new-yellow.png');
            $pc3 = URL::asset('img/cart/step3-new-gray.png');
            $pc4 = URL::asset('img/cart/step4-new-gray.png');
            $mobile1 = URL::asset('img/cart/step31.png');
            $mobile2 = URL::asset('img/cart/step22.png');
            $mobile3 = URL::asset('img/cart/step33.png');
            $mobile4 = URL::asset('img/cart/no-step-4-gray.png');
        } else if ($page == 3) {
            $pc1 = URL::asset('img/cart/step11.png');
            $pc2 = URL::asset('img/cart/step2-new-gray.png');
            $pc3 = URL::asset('img/cart/step3-new-yellow.png');
            $pc4 = URL::asset('img/cart/step4-new-gray.png');
            $mobile1 = URL::asset('img/cart/step31.png');
            $mobile2 = URL::asset('img/cart/step32.png');
            $mobile3 = URL::asset('img/cart/step23.png');
            $mobile4 = URL::asset('img/cart/no-step-4-gray.png');
        } else {
            $pc1 = URL::asset('img/cart/step11.png');
            $pc2 = URL::asset('img/cart/step2-new-gray.png');
            $pc3 = URL::asset('img/cart/step3-new-gray.png');
            $pc4 = URL::asset('img/cart/step4-new-yellow.png');
            $mobile1 = URL::asset('img/cart/step31.png');
            $mobile2 = URL::asset('img/cart/step32.png');
            $mobile3 = URL::asset('img/cart/step33.png');
            $mobile4 = URL::asset('img/cart/no-step-4-yellow-2.png');
        }
        $click = '#';
        if($authStep3 != ''){
            $click = '/paymentending';
        }
$html = '
        <div class="row ">
            <div class="offset-md-4 offset-2"></div>
            <div class="col-md-4 col-8">
                <div class="row">
                    <div class="col-md-3 col-3 top2rem d-block d-sm-none">
                        <a href="' . url('cart') . '"> <img class="img-fluid d-block w-100" src="' . $mobile1 . '"></a>
                    </div>
                    <div class="col-md-3 col-3 top2rem d-block d-sm-none">
                       <a href="' . url('paymentstarting') . '">  <img class="img-fluid d-block w-100" src="' . $mobile2 . '"></a>
                    </div>
                    <div class="col-md-3 col-3 top2rem d-block d-sm-none">
                        <a href="'.$click.'"> <img class="img-fluid d-block w-100" src="' . $mobile3 . '"></a>
                    </div>
                     <div class="col-md-3 col-3 top2rem d-block d-sm-none">
                        <img class="img-fluid d-block w-100" src="' . $mobile4 . '">
                    </div>
                </div>
            </div>
        </div>';
        return $html;
    }
}

if (!function_exists('unsetAdmin')) {
    function unsetAdmin()
    {
        Session::forget('id_admin');
        Session::forget('name_admin');
        Session::forget('lastname_admin');
        Session::forget('status_admin');
        Session::forget('main_id_at');
        Session::forget('email_admin');
        Session::forget('file_img_admin');
        Session::forget('username_admin');
    }
}

if (!function_exists('left_sub_menu')) {
    function left_sub_menu($text, $link, $icon, $active)
    {
        return '<li class="nav-item">
                            <a href="' . $link . '" class="nav-link ' . $active . '">
                                <i class="fa ' . $icon . '"></i>
                                <p>' . $text . '</p>
                            </a>
                        </li>';
    }
}

if (!function_exists('storeAsMake')) {
    function storeAsMake($path)
    {
        return 'local/storage/app/public/' . $path;
    }
}

if (!function_exists('left_menu')) {
    function left_menu($text, $text2, $link, $icon, $classSmall, $active)
    {
        return '<li class="nav-item">
                    <a href="' . $link . '" class="nav-link ' . $active . '">
                        <i class="' . $icon . '"></i>
                        <p>
                            ' . $text . '
                            <span class="right badge badge-' . $classSmall . '">' . $text2 . '</span>
                        </p>
                    </a>
                </li>';
    }
}
if (!function_exists('inputSelect2')) {
    function inputSelect2($text, $name, $id, $class, $classTop, $null, $option)

    {
        return '<div class="col-xs-12 col-' . $classTop . '">
                        <div class="form-group">
                            <label>' . $text . '</label>
                            <select name="' . $name . '" id="' . $id . '" class="form-control select2 ' . $class . '" ' . $null . '>
                           ' . $option . '
                            </select>
                        </div>
                    </div>';
    }
}
if (!function_exists('inputSelect')) {
    function inputSelect($text, $name, $id, $class, $classTop, $null, $option)

    {
        return '<div class="col-xs-12 col-' . $classTop . '">
                        <div class="form-group">
                            <label>' . $text . '</label>
                            <select name="' . $name . '" id="' . $id . '" class="form-control  ' . $class . '" ' . $null . '>
                           ' . $option . '
                            </select>
                        </div>
                    </div>';
    }
}
if (!function_exists('inputCheckbox')) {
    function inputCheckbox($text, $name, $id, $checked, $value)
    {
        return '<div style="margin-left: 30px">

                                        <label class="label-checkbox">' . $text . '

                                            <input type="checkbox" name="' . $name . '" id="' . $id . '" ' . $checked . ' value="' . $value . '">

                                            <span class="checkmark"></span>

                                        </label>

                                    </div>';
    }
}
if (!function_exists('inputSetImage')) {
    function inputSetImage($text, $name, $id, $checked, $value)
    {
        return '<div class="form-group">

                                        <label class="label-checkbox">' . $text . '
' . ($text == '' ? '' : ' <input type="radio" name="' . $name . '" id="' . $id . '" ' . $checked . ' value="' . $value . '">') . '
                                           

                                             &nbsp;&nbsp;&nbsp;&nbsp;<a  href="javascript:void;" onclick="del(' . $value . ')" title="ลบรูปภาพนี้" class="btn btn-flat btn-outline-danger">

                                             <i class="fas fa-trash" aria-hidden="true"></i></a>
' . ($text == '' ? '' : ' <span class="checkmark"></span>') . '
                                        </label>

                                    </div>';
    }
}

if (!function_exists('inputText')) {
    function inputText($text, $name, $id, $placeholder, $classTop, $null, $value)
    {
        return '<div class="col-xs-12 col-' . $classTop . '">

                        <div class="form-group">
                            <label>' . $text . '</label>
                        <input type="text" value="' . $value . '" name="' . $name . '" id="' . $id . '" class="form-control" ' . $null . ' placeholder="' . $placeholder . '">
                        </div>
                    </div>';
    }
}
if (!function_exists('inputNumber')) {
    function inputNumber($text, $name, $id, $placeholder, $classTop, $null, $value)
    {
        return '<div class="col-xs-12 col-' . $classTop . '">
                        <div class="form-group">
                            <label>' . $text . '</label>
                        <input type="number" value="' . $value . '" name="' . $name . '" id="' . $id . '" class="form-control" ' . $null . ' placeholder="' . $placeholder . '">
                        </div>
                    </div>';
    }
}

if (!function_exists('inputEmail')) {
    function inputEmail($text, $name, $id, $placeholder, $classTop, $null, $value)
    {
        return '  <div class="col-xs-12 col-' . $classTop . '">

                                    <div class="form-group">

                                        <label>' . $text . '</label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">

                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>

                                            </div>

                                            <input type="email" ' . $null . ' value="' . $value . '" class="form-control " placeholder="' . $placeholder . '" name="' . $name . '"

                                                   id="' . $id . '">

                                        </div>

                                    </div>

                                </div>';
    }
}

if (!function_exists('inputPassword')) {
    function inputPassword($text, $name, $id, $placeholder, $classTop, $null, $value)
    {
        return '  <div class="col-xs-12 col-' . $classTop . '">

                                    <div class="form-group">

                                        <label>' . $text . '</label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">

                                                <span class="input-group-text"><i class="fas fa-key"></i></span>

                                            </div>

                                            <input type="password" ' . $null . ' value="' . $value . '" class="form-control " placeholder="' . $placeholder . '" name="' . $name . '"

                                                   id="' . $id . '">

                                        </div>

                                    </div>

                                </div>';
    }
}
if (!function_exists('inputTimeRank')) {
    function inputTimeRank($text, $name, $id, $placeholder, $classTop, $null, $value)
    {
        return '  <div class="col-xs-12 col-' . $classTop . '">

                                    <div class="form-group">

                                        <label>' . $text . '</label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">

                                                <span class="input-group-text"><i class="far fa-clock"></i></span>

                                            </div>

                                            <input type="text" ' . $null . ' value="' . $value . '" class="form-control " placeholder="' . $placeholder . '" name="' . $name . '"

                                                   id="' . $id . '">

                                        </div>

                                    </div>

                                </div>';
    }
}
if (!function_exists('inputTextArea')) {
    function inputTextArea($text, $name, $id, $ckeditor, $classTop, $null, $value)
    {
        return '<div class="col-xs-12 col-' . $classTop . '" >
                        <div class="form-group">
                            <label>' . $text . '</label>
                        <textarea  name="' . $name . '" id="' . $id . '" class="form-control ' . $ckeditor . '" ' . $null . '>' . $value . '</textarea>
                        </div>
                    </div>';
    }
}

if (!function_exists('uploadMultipleImage')) {
    function uploadMultipleImage($file, $text, $sizeText, $classDiv, $width, $height)
    {
        $html = ' <div class="col-' . $classDiv . '" style="margin-bottom: 5px">
 <center><div class="form-group">
 <div class="card bg-dark text-white" style="width:' . $width . ' ;height: ' . $height . '!important;">
  <img  src="' . $file . '" id="img-preview2" style="width:' . $width . ' ;height: ' . $height . '!important;" class="">
  <div class="card-img-overlay "><br><br>
    <h4 class="text-center" >' . $sizeText . '</h4>
  </div>
</div>                    
                            </div>
                            <input type="file" accept="image/png, image/jpg, image/gif, image/jpeg" id="fileUpload2"  name="files[]" multiple class="filestyle" data-buttonName="btn-success"

                                   data-icon="false" data-buttonText="' . $text . '" data-input="false"  onchange="readURL2(this)" ></center></div>';
        return $html;
    }
}
if (!function_exists('uploadSingleImage')) {
    function uploadSingleImage($file, $text, $sizeText, $classDiv, $width, $height)
    {
        $html = ' <div class="col-' . $classDiv . '" style="margin-bottom: 5px">
 <center><div class="form-group">
 <div class="card bg-dark text-white" style="width:' . $width . ' ;height: ' . $height . '!important;">
  <img  src="' . $file . '" id="img-preview" style="width:' . $width . ' ;height: ' . $height . '!important;" class="">
  <div class="card-img-overlay "><br><br>
    <h4 class="text-center" >' . $sizeText . '</h4>
  </div>
</div>                    
</div>
<input type="file" accept="image/png, image/jpg, image/gif, image/jpeg" id="fileUpload"  name="fileToUpload" class="filestyle" data-buttonName="btn-success"
 data-icon="false" data-buttonText="' . $text . '" data-input="false"  onchange="readURL(this)" ></center></div>';

        return $html;
    }
}

if (!function_exists('uploadSingleImage2')) {
    function uploadSingleImage2($file, $text, $sizeText, $classDiv, $width, $height)
    {
        $html = ' <div class="col-' . $classDiv . '" style="margin-bottom: 5px">
 <center><div class="form-group">
 <div class="card bg-dark text-white" style="width:' . $width . ' ;height: ' . $height . '!important;">
  <img  src="' . $file . '" id="img-preview2" style="width:' . $width . ' ;height: ' . $height . '!important;" class="">
  <div class="card-img-overlay "><br><br>
    <h4 class="text-center" >' . $sizeText . '</h4>
  </div>
</div>                    
</div>
<input type="file" accept="image/png, image/jpg, image/gif, image/jpeg" id="fileUpload2"  name="fileToUpload2" class="filestyle" data-buttonName="btn-success"
 data-icon="false" data-buttonText="' . $text . '" data-input="false"  onchange="readURL2(this)" ></center></div>';

        return $html;
    }
}

if (!function_exists('insertSingleImage')) {
    function insertSingleImage($request, $path)
    {
        $fileName = "fileName" . rand() . time() . '.' . $request->fileToUpload->getClientOriginalExtension();
        $request->fileToUpload->storeAs('' . $path . '', $fileName);
        return $fileName;
//        ->with('success','You have successfully upload image.');
    }
}

if (!function_exists('insertSingleImage2')) {
    function insertSingleImage2($request, $path)
    {
        $fileName2 = "fileName" . rand() . time() . '.' . $request->fileToUpload2->getClientOriginalExtension();
        $request->fileToUpload2->storeAs('' . $path . '', $fileName2);
        return $fileName2;
//        ->with('success','You have successfully upload image.');
    }
}

if (!function_exists('insertMultipleImage')) {
    function insertMultipleImage($request, $path, $name)
    {
        $images = [];
        $fileName = $request->file('' . $name . '');
        foreach ($fileName as $file) {
//            $name = rand() . time() . $file->getClientOriginalName();
            $name = rand() . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('' . $path . '', $name);
            $images[] = $name;
        }
        return $images;
//        ->with('success','You have successfully upload image.');
    }
}

if (!function_exists('alertSuccess')) {
    function alertSuccess($title, $message, $link)
    {
        echo '<script>
        alertify.alert(\'' . $title . '\', \'' . $message . '\', function(){ alertify.success(\'Ok\');window.location="' . $link . '" });
    </script>';
    }
}
if (!function_exists('messageError')) {
    function messageError($message)
    {
        echo '<script>
          alertify.error(\'' . $message . '\');
    </script>';
    }
}
if (!function_exists('messageSuccess')) {
    function messageSuccess($message)
    {
        echo '<script>
          alertify.success(\'' . $message . '\');
    </script>';
    }
}
if (!function_exists('alertConfirm')) {
    function alertConfirm($message, $function)
    {
        echo '<script>
         alertify.confirm("' . $message . '",
  function(){
             ' . $function . '
    alertify.success(\'success\');
  },
  function(){
    alertify.error(\'error\');
  });
    </script>';
    }
}
if (!function_exists('buttonManageData')) {
    function buttonManageData($id, $view, $edit, $delete, $route)
    {

        $html = '<center>';
        if ($view === true) {
            $html .= ' <a title="View" href="#" onclick="viewShow(' . $id . ')" class="btn btn-flat btn-outline-primary "><i class="far fa-eye"></i></a>';
        }
        if ($edit === true) {
            $html .= ' <a title="Edit"  href="' . url('' . $route . '/' . $id . '/edit') . '" class="btn btn-flat btn-outline-warning"><i><i class="far fa-edit"></i></a>';
        }
        if ($delete === true) {
            $html .= ' <a title="Delete" href="#" onclick="deleteData(' . $id . ')" class="btn btn-flat btn-outline-danger"><i><i class="far fa-trash-alt"></i></a>';
        }
        $html .= '</center>';
        return $html;
    }
}

if (!function_exists('buttonReport')) {
    function buttonReport($url, $report, $id)
    {

        $html = '<center>';

        if ($report === true) {
            $html .= ' <a title="showPDF" href="' . $url . '/' . $id . '"  class="btn btn-flat btn-outline-success"><i class="fas fa-print"></i></a>';
        }

        $html .= '</center>';
        return $html;
    }
}

//frontEnd
if (!function_exists('salePercen')) {
    function salePercen($priceAgent, $sale)
    {
        $total = @round(($sale * 100) / $priceAgent);
        if($total == ''){
            $total = 0;
        }
        return $total;
    }
}