<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrder extends FormRequest
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
        // return dd([$this]);
        return [
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required',
            'products.*.notes' => 'nullable|string',
            'products.*.variation' => 'nullable',
            'discount' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'type' => 'nullable|string',
            'status' => 'nullable|string',
            'table_no' => 'nullable|string',
            'table_id' => 'nullable|integer|exists:rtables,id',
            'total_price' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'order_type' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'final_total' => 'nullable|numeric',
            'restaurant_id' => 'nullable|integer|exists:restaurants,id', // If order are restaurant-specific
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        // Customize the error response if validation fails
        $error = $validator->errors()->first();

        // Create a custom error response object or structure
        $response = [
            'bool' => false,
            'status' => 422,
            "message" => $error,
            "result" => null
        ];

        // Throw an HttpResponseException with the custom response
        throw new HttpResponseException(response()->json($response, 422));
    }
}
