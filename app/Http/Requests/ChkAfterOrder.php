<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChkAfterOrder extends FormRequest
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
            'email' => 'required',
            'address' => 'required',
            'province' => 'not_in:0',
            'ampuhers' => 'required',
            'districts' => 'required',
            'postcode' => 'required',
            'email_voucher' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'firstName.required' => 'กรุณากรอกข้อมูล ชื่อ',
            'lastName.required' => 'กรุณากรอกข้อมูล นามสกุล',
            'tel.required' => 'กรุณากรอกข้อมูล เบอร์โทรศัพท์',
            'email.required' => 'กรุณากรอกข้อมูล E-mail',
            'address.required' => 'กรุณากรอกข้อมูล ที่อยู่',
            'province.not_in' => 'กรุณาเลือกข้อมูล จังหวัด',
            'ampuhers.required' => 'กรุณาใส่ข้อมูล เขต/อำเภอ',
            'districts.required' => 'กรุณาใส่ข้อมูล แขวง/ตำบล',
            'postcode.required' => 'กรุณากรอกข้อมูล รหัสไปรษณีย์',
            'email_voucher.required' => 'กรุณากรอกข้อมูล E-mail สำหรับรับข้อมูลการสั่งซ์้อ',

        ];

    }
}
