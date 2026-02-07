<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
            'category' => 'required',
            'condition' => 'required',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.string' => '商品名は文字列で入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.string' => '商品の説明は文字列で入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '商品画像はJPEGまたはPNG形式でアップロードしてください',
            'image.mimes' => '商品画像はJPEGまたはPNG形式でアップロードしてください',
            'image.max' => '画像サイズは2MB以内にしてください',
            'category.required' => 'カテゴリーを選択してください',
            'condition.required' => 'コンディションを選択してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',
        ];
    }
}
