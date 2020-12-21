<?php



namespace App\Http\Requests\admin;



use Illuminate\Foundation\Http\FormRequest;



class UpdateAdminRequest extends FormRequest

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
            'rePassword' => 'required_with:password|same:password',

        ];
    }



    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกข้อมูล ชื่อ',
            'last_name.required' => 'กรุณากรอกข้อมูล นามสกุล',
            'rePassword.required_with' => 'กรุณากรอกข้อมูล ยืนยันรหัสผ่าน',
            'rePassword.same' => 'รหัสผ่านไม่เหมือนกัน',


        ];

    }

}

