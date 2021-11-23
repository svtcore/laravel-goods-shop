<?php

namespace App\Http\Requests\admin\payments;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'payment_name' => 'required|regex:/^[a-zа-я0-9À-ÿẞ\s."№,-]+$/i|min:2|max:50',
            'payment_exst' => 'required|digits_between:0,1',
        ];
    }
}
