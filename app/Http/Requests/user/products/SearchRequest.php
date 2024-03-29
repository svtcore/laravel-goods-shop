<?php

namespace App\Http\Requests\user\products;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query' => 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%?!:;,-]+$/i|min:1|max:255',
        ];
    }
}
