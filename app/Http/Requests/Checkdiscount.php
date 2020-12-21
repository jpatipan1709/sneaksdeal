<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkdiscount extends FormRequest
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
//             'code_discount' => 'required|unique:tb_discount,discount_code|regex:/^[a-zA-Z0-9\s]+$/',
             'qty' => 'required|numeric',
             'discount_min' => 'required|numeric',
             'discount_bath' => 'required|numeric',
             'date_start' => 'required',
             'date_end' => 'required',
        ];
    }

    public function messages()
     {
         return [
             'qty.required' => 'กรุณาใส่ข้อมูล จำนวน',
             'discount_min.required' => 'กรุณาใส่ข้อมูล ยอดขั้นต่ำ',
             'discount_bath.required' => 'กรุณาใส่ข้อมูล ส่วนลด',
             'qty.numeric' => 'กรุณาใส่เบอร์โทรด้วย อักขระตัวเลขเท่านั้น',
             'discount_min.numeric' => 'กรุณาใส่เบอร์โทรไม่ต่ำกว่า 9 ตัว',
             'discount_bath.numeric' => 'กรุณาใส่เบอร์โทรไม่ต่ำกว่า 9 ตัว',
             'date_start.required' => 'กรุณาใส่ข้อมูล วันที่เริ่ม',
             'date_end.required' => 'กรุณาใส่ข้อมูล วันที่ยกเลิก',
         ];
//         'code_discount.required' => 'กรุณาใส่ข้อมูล รหัสส่วนลด',
//             'code_discount.regex' => 'กรุณาใส่ข้อมูล อักษรอังกฤษ และตัวเลขเท่านั้น',
//             'code_discount.unique' => 'มีรหัสส่วนลดนี้อยู่แล้ว',
     }
}
