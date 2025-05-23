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
        return [
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.notes' => 'nullable|string',
            'products.*.variation' => 'nullable|array',
            'discount' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|in:dine-in,take-away,delivery,drive-thru,curbside-pickup,catering,reservation',
            'table_id' => 'nullable|exists:rtables,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:pending,confirmed,preparing,ready_for_pickup,out_for_delivery,delivered,completed,cancelled',
            'payment_method' => 'nullable|string',
            'order_type' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'restaurant_id' => 'required|exists:restaurants,id',
            'coupon_code' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'final_total' => 'nullable|numeric',
            'tax_percentage' => 'nullable|numeric|min:0', // Add this line
            'tax_amount' => 'nullable|numeric|min:0',       // Add this line
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
