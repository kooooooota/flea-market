<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'user_name' => [
                'required',
                'string',
                'max:20',
            ],
            'zip_code' => [
                'required',
                'string',
                'max:8',
            ],
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'building' => [
                'nullable',
                'string',
                'max:255',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
                'max:2048',
                'regex:/^.*\.(jpg|jpeg|png)$/i',
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => 'ユーザー名を入力してください',
            'user_name.max' => 'ユーザー名は20文字以内で入力してください',
            'zip_code.required' => '郵便番号を入力してください',
            'zip_code.max' => '郵便番号は8文字以内（ハイフン含む）で入力してください',
            'address.required' => '住所を入力してください',
            'address.max' => '住所は255文字以内で入力してください',
            'building.max' => '建物名は255文字以下で入力してください',
            'image.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください', 
            'image.regex' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください', 
        ];
    }
}
