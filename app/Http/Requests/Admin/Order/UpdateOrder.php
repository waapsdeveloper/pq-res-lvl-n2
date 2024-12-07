<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:15',
            'discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|string|in:pending,completed,cancelled', // Add more statuses if needed
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();

        $response = [
            'bool' => false,
            'status' => 422,
            'message' => $error,
            'result' => null,
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
