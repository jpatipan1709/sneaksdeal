<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequests extends FormRequest
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
            'name' => 'required',
            'last_name' => 'required',
            // 'rePassword' => 'required_with:password|same:password|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณาใส่ข้อมูล ชื่อ',
            'last_name.required' => 'กรุณาใส่ข้อมูล นามสกุล',
            'tel.required' => 'กรุณาใส่ข้อมูล เบอร์โทร',
            // 'rePassword.required_with' => 'กรุณากรอก รหัสผ่าน',
            // 'rePassword.same' => 'รหัสผ่านของท่านไม่เหมือนกัน',
            // 'rePassword.min' => 'รหัสผ่านอย่างน้อย 8 ตัวอักษร',

        ];

    }
}
