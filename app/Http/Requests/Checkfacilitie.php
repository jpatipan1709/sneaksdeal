<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkfacilitie extends FormRequest
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
             'fileToUpload' => 'required',
             'nameFacilities' => 'required',
             
            
        ];
    }

    public function messages()
     {
         return [
             'fileToUpload.required' => 'กรุณาเลือกรูปภาพ',
             'namnameFacilitiese.required' => 'กรุณาใส่ข้อมูล ชื่อสิ่งอำนวยความสะดวก',
         ];
 
     }
}
