<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkvoucher extends FormRequest
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

            
             'facilities' => 'required_without_all',
             
            
        ];
    }

    public function messages()
     {
         return [
            
           
             'facilities.required_without_all' => 'กรุณาเลือกข้อมูล สิ่งอำนวยความสะดวก',
         ];
 
     }
}
