<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationResquest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users',
            'gender' => 'sometimes|max:20',
            'id_role' => 'required|numeric',
            'image' => 'sometimes|mimes:jpeg,jpg,png,gif',
            'password' => 'required|min:6',
        ];
    }
}
