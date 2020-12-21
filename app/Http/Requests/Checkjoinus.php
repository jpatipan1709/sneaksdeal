<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkjoinus extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'mail' => 'required',
             'name' => 'required',
             'hotel' => 'required',
             'tel' => 'required|numeric|digits_between:9,10',
             'comment' => 'required',
            
        ];
    }

    public function messages()
     {
         return [
             'mail.required' => 'กรุณาใส่ข้อมูล อีเมล์',
             'name.required' => 'กรุณาใส่ข้อมูล ชื่อ',
             'tel.required' => 'กรุณาใส่ข้อมูล เบอร์โทร',
             'tel.numeric' => 'กรุณาใส่เบอร์โทรด้วย อักขระตัวเลขเท่านั้น',
             'tel.digits_between' => 'กรุณาใส่เบอร์โทรไม่ต่ำกว่า 9 ตัว',
             'hotel.required' => 'กรุณาใส่ข้อมูล ชื่อโรงแรม หรือสถานที่พัก',
             'comment.required' => 'กรุณาใส่ข้อมูล รายละเอียด',
         ];
 
     }
}
