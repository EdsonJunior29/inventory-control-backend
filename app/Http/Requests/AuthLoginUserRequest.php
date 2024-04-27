<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginUserRequest extends FormRequest
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
            'email' => ['required', 'string' , 'email'],
            'password' => ['required', 'string', 'min:8']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Field is required',
            'email.string' => 'The email field must be a string',
            'email.email' => 'The email field must be a valid email address',
            'password.required' => 'The password field is mandatory',
            'password.string' => 'The password field must be a string',
            'password.min' => 'The password field must be at least :min characters',
        ];
    }
}
