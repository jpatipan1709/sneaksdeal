<?php



namespace App\Http\Requests\admin;



use Illuminate\Foundation\Http\FormRequest;



class AdminRequests extends FormRequest

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
            'email' => 'required|unique:system_admin,email',
            'password' => 'required|min:8',
            'rePassword' => 'required|same:password|min:8',
            'fileToUpload' => 'required|file|max:10240|mimes:jpeg,bmp,png,gif'

        ];
    }



    public function messages()
    {
        return [
            'email.required' => 'กรุณากรอกข้อมูล E-mail',
            'email.unique' => 'E-mail นี้มีผู้ใช้งานแล้ว',
            'name.required' => 'กรุณากรอกข้อมูล ชื่อ',
            'last_name.required' => 'กรุณากรอกข้อมูล นามสกุล',
            'tel.required' => 'กรุณากรอกข้อมูล เบอรโทรศัพท์',
            'password.required' => 'กรุณากรอกข้อมูล ยืนยันรหัสผ่าน',
            'rePassword.required' => 'กรุณากรอกข้อมูล รหัสผ่านอีกครั้ง',
            'password.min' => 'ความยาว รหัสผ่าน ไม่ควรต่ำกว่า 8 ตัวอักษร',
            'rePassword.min' => 'ความยาว รหัสผ่านอีกครั้ง ไม่ควรต่ำกว่า 8 ตัวอักษร',
            'rePassword.same' => 'รหัสผ่านไม่เหมือนกัน',
            'fileToUpload.required' => 'กรุณาเลือกรูปภาพ',
            'fileToUpload.max' => 'ขนาดรูปไม่เกิน 10 MB',
            'fileToUpload.mimes' => 'ประเภทรูปต้องเป็น jpeg,bmp,png,gif',

        ];

    }

}

