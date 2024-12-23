<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUser extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string', // You can add regex here for phone number validation
            'password' => 'required|string|min:6', // Add validation for password
            'role' => 'required|integer', // Ensure role is provided
            'status' => 'required|string|in:active,inactive',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp|max:2048',
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
