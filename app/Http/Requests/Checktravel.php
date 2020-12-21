<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checktravel extends FormRequest
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
            'travel_name' => 'required',
            // 'blogid' =>'required_without_all',
        ];
    }

    public function messages()
    {
        return [
            'travel_name.required' => 'กรุณาใส่ข้อมูล  Travel Guide Tag',
            'travel_name.unique' => 'มีชื่อ Travel Guide Tag นี้แล้ว',
            // 'blogid.required_without_all' => 'กรุณาเลือก Blog ที่จะใช้แสดง',
        ];

    }
}
