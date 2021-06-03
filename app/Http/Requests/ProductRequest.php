<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'file_image_1' => 'nullable|mimes:jpg,jpeg,png,bmp|max:3000',
            'file_image_2' => 'nullable|mimes:jpg,jpeg,png,bmp|max:3000',
            'file_image_3' => 'nullable|mimes:jpg,jpeg,png,bmp|max:3000',
            'file_image_4' => 'nullable|mimes:jpg,jpeg,png,bmp|max:3000',
            'product_name_en' => 'required|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:50',
            'product_name_de' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:50',
            'product_name_uk' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:50',
            'product_name_ru' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:50',
            'product_description_en' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:1000',
            'product_description_de' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:1000',
            'product_description_uk' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:1000',
            'product_description_ru' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.№%",-]+$/i|min:2|max:1000',
            'product_price' => 'required|digits_between:1,100000',
            'product_weight' => 'required|between:1,100000',
            'product_categ' => 'required|digits_between:1,100000',
            'product_exst' => 'required|between:0,1',
        ];
    }
}
