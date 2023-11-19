<?php

namespace App\Http\Requests\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $strings = 'required|string';
        // $expiredValidation = date('Y-m', time());
        return [
            'name' => $strings,
            'email' => 'required|email|unique:users,email,' . Auth::id() . ',id',
            'occupation' => $strings,
            'phone' => $strings,
            'address' => $strings,
            'discount' => 'nullable|string|exists:discounts,code,deleted_at,NULL'


        ];
    }
}
