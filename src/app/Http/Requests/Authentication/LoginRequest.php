<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'     => 'required',
            'password'  => 'required',
        ];
    }

    /**
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Email address is required',
            'password.required' => 'Password is required',
        ];
    }
}
