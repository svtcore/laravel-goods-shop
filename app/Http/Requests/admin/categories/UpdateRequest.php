<?php

namespace App\Http\Requests\admin\categories;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'category_name_en' => 'required|regex:/^[a-zа-я0-9À-ÿẞ\s.,-]+$/i|min:2|max:50',
            'category_name_de' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.,-]+$/i|min:2|max:50',
            'category_name_uk' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.,-]+$/i|min:2|max:50',
            'category_name_ru' => 'nullable|regex:/^[a-zа-я0-9À-ÿẞ\s.,-]+$/i|min:2|max:50',
        ];
    }
}
