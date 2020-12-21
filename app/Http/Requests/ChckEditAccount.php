<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChckEditAccount extends FormRequest
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
             'email' => 'required',
             'firstName' => 'required',
             'lastName' => 'required',
             'tel' => 'required|numeric',
             'new_password' => 'nullable|min:8|regex:/^[a-zA-Z0-9\s]+$/',
             'confirm_new_password' => 'nullable|min:8|regex:/^[a-zA-Z0-9\s]+$/|same:new_password',
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
             'tel.numeric' => 'กรุณาใส่เบอร์โทรด้วย อักขระตัวเลขเท่านั้น',
             'new_password.required' => 'กรุณาใส่ข้อมูล รหัสผ่าน',
             'new_password.min' => 'รหัสผ่านจะต้องมีความยาวอย่างน้อย :min อักขระ ภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             'confirm_new_password.required' => 'กรุณาใส่ข้อมูล ยืนยันรหัสผ่าน',
             'confirm_new_password.min' => 'ยืนยันรหัสผ่านจะต้องมีความยาวอย่างน้อย :min อักขระ ภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             'new_password.regex' => 'กรุณาใส่รหัสผ่านด้วย  อักขระภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             'new_password.confirmed' => 'กรุณาใส่รหัสผ่านด้วย  และยืนยันรหัสผ่านให้ ตรงกัน',
             'confirm_new_password.regex' => 'กรุณาใส่ยืนยันรหัสผ่านอีกครั้งด้วย  อักขระภาษาอังกฤษ หรือตัวเลขเท่านั้น',
             'confirm_new_password.same' => 'กรุณาใส่รหัสผ่านใหม่ และยืนยันรหัสผ่านใหม่ ให้เหมือนกัน',
         ];
 
     }
}
