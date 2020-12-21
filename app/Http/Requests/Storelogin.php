<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storelogin extends FormRequest
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
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'กรุณากรอกข้อมูล อีเมล์',

            'password.required' => 'กรุณากรอกข้อมูล ยืนยันรหัสผ่าน',
            'password.min' => 'ความยาว รหัสผ่าน ไม่ควรต่ำกว่า :min ตัวอักษร',
        ];
    }
}
