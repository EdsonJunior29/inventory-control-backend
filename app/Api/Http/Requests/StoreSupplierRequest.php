<?php

namespace App\Api\Http\Requests;

use App\Api\Rules\CnpjRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:50'],
            'phone' => ['string', 'max:25'],
            'cnpj' => ['required', 'unique:suppliers', 'string', 'max:50', new CnpjRule()],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than :max characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than :max characters.',
            'phone.string' => 'The phone field must be a string.',
            'phone.max' => 'The phone field may not be greater than :max characters.',
            'cnpj.required' => 'The cnpj field is required.',
            'cnpj.unique' => 'The cnpj has already been taken.',
            'cnpj.string' => 'The cnpj field must be a string.',
            'cnpj.max' => 'The cnpj field may not be greater than :max characters.',
        ];
    }
}