<?php



namespace App\Http\Requests\admin;



use Illuminate\Foundation\Http\FormRequest;



class BlogRequests extends FormRequest

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
            'type_blog' => 'required_without_all',
        ];
    }



    public function messages()
    {
        return [
            'type_blog.required_without_all' => 'กรุณาเลือก tag ',



        ];

    }

}

