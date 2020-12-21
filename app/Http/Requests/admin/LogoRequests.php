<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class LogoRequests extends FormRequest
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
            'fileToUpload' => 'required|file|max:10240|mimes:jpeg,bmp,png,gif',
        ];
    }

    public function messages()
    {
        return [
            'fileToUpload.required' => 'File Not null',
            'fileToUpload.max' => 'Max size 10 MB',
            'fileToUpload.mimes' => 'type jpeg,bmp,png,gif',
        ];

    }
}
