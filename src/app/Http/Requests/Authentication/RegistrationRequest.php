<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string',
            'username'      => 'required|alpha_num|unique:users',
            'email'         => 'required|max:255|email|unique:users',
            'password'      => 'required|confirmed',
        ];
    }

    /**
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'name.required'         => 'Name is required!',
            'username.required'     => 'username is required!',
            'username.alpha_num'    => 'Username should contain only alphabets and numbers!',
            'username.unique'       => 'Username has already been taken!',
            'email.required'        => 'Email address is required!',
            'email.unique'          => 'This email has been used already!',
            'password.required'     => 'Password is required',
        ];
    }
}
