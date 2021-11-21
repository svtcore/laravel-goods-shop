<?php

namespace App\Http\Requests\user\orders;

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
            'f_name' => 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50',
            'l_name' => 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50',
            'phone' => 'required|digits_between:10,25|min:10|max:25',
            'street' => 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:2|max:100',
            'house' => 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:1|max:5',
            'appart' => 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:1|max:5',
            'entrance' => 'nullable|digits_between:1,5|min:1|max:5',
            'code' => 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№,-]+$/i|min:1|max:10',
            'payment' => 'required|digits_between:1,100|min:1|max:3',
            'note' => 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%()?!:;=,-]+$/i|min:1|max:255',
            //'status' => 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50',
        ];
    }
}
