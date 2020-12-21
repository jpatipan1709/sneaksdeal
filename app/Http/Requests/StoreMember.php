<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMember extends FormRequest
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
            'firstName' => 'required',
            'lastName' => 'required',
            'tel' => 'required',
            'email' => 'required|unique:tb_member',
            'password' => 'required|min:8',
            're_password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'กรุณากรอกข้อมูล E-mail',
            'email.unique' => 'E-mail นี้มีผู้ใช้งานแล้ว',


            'firstName.required' => 'กรุณากรอกข้อมูล ชื่อ',
            'lastName.required' => 'กรุณากรอกข้อมูล นามสกุล',
            'tel.required' => 'กรุณากรอกข้อมูล เบอรโทรศัพท์',
            'password.required' => 'กรุณากรอกข้อมูล ยืนยันรหัสผ่าน',
            're_password.required' => 'กรุณากรอกข้อมูล รหัสผ่านอีกครั้ง',

            'password.min' => 'ความยาว รหัสผ่าน ไม่ควรต่ำกว่า 8 ตัวอักษร',
            're_password.min' => 'ความยาว รหัสผ่านอีกครั้ง ไม่ควรต่ำกว่า 8 ตัวอักษร',
        ];
    }
}
