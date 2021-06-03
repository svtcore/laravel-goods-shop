<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerUpdateRequest extends FormRequest
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
            'm_name' => 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50',
            'phone' => 'required|digits_between:10,25',
            'email' => 'required|regex:/^[a-z0-9.-@]+$/i|min:5|max:100',
            'password' => 'nullable|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/i|min:2|max:50',
        ];
    }
}
