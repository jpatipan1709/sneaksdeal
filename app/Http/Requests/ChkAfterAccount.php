<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChkAfterAccount extends FormRequest
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
             'email' => 'required|unique:tb_member,email',
             'firstName' => 'required',
             'lastName' => 'required',
             'tel' => 'required|min:10|regex:/^[0-9\s]+$/',
             'password' => 'required|min:8|regex:/^[a-zA-Z0-9\s]+$/',
             're_password' => 'required|min:8|regex:/^[a-zA-Z0-9\s]+$/|same:password',
         ];
     }
 
     public function messages()
     {
         return [
             'email.required' => 'กรุณาใส่ข้อมูล อีเมล์',
             'email.unique' => 'มีผู้ใช้งานอีเมล์นี้แล้ว',
             'firstName.required' => 'กรุณาใส่ข้อมูล ชื่อ',
             'lastName.required' => 'กรุณาใส่ข้อมูล นามสกุล',
             'tel.required' => 'กรุณาใส่ข้อมูล เบอร์โทร',
             'tel.regex' => 'กรุณาใส่เบอร์โทรด้วย อักขระตัวเลขเท่านั้น',
             'tel.min' => 'กรุณาใส่เบอร์โทรให้ครบ :min หลัก',
             'password.required' => 'กรุณาใส่ข้อมูล รหัสผ่าน',
             'password.min' => 'รหัสผ่านจะต้องมีความยาวอย่างน้อย :min อักขระ ภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             're_password.required' => 'กรุณาใส่ข้อมูล ยืนยันรหัสผ่าน',
             're_password.min' => 'ยืนยันรหัสผ่านจะต้องมีความยาวอย่างน้อย :min อักขระ ภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             'password.regex' => 'กรุณาใส่รหัสผ่านด้วย  อักขระภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             'password.confirmed' => 'กรุณาใส่รหัสผ่านด้วย  และยืนยันรหัสผ่านให้ ตรงกัน',
             're_password.regex' => 'กรุณาใส่ยืนยันรหัสผ่านอีกครั้งด้วย  อักขระภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             're_password.same' => 'กรุณาใส่รหัสผ่าน และยืนยันรหัสผ่าน ให้เหมือนกัน',
         ];
 
     }
}
