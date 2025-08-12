<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer'],
            'description' => ['string', 'max:255'],
            'quantity_in_stock' => ['required', 'integer'],
            'status_id' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than :max characters.',
            'brand.required' => 'The brand field is required.',
            'brand.string' => 'The brand field must be a string.',
            'brand.max' => 'The brand field may not be greater than :max characters.',
            'category_id.required' => 'The category_id field is required.',
            'category_id.integer' => 'The category_id field must be a integer.',
            'description.max' => 'The brand field may not be greater than :max characters.',
            'quantity_in_stock.required' => 'The quantity_in_stock field is required.',
            'quantity_in_stock.integer' => 'The quantity_in_stock field must be a integer.',
            'status_id.required' => 'The status_id field is required.',
            'status_id.integer' => 'The status_id field must be a integer.',
        ];
    }
}