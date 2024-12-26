<?php

namespace App\Http\Requests\Admin\Rtable;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRtable extends FormRequest
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
            'name' => 'nullable|string',
            'restaurant_id' => 'nullable|integer|exists:restaurants,id',
            'identifier' => 'nullable|string|unique:rtables,identifier|min:3|max:255',
            'no_of_seats' => 'nullable|integer|max:255',
            'description' => 'nullable|string|max:500',
            'floor' => 'nullable|string|max:500',
            'status' => 'nullable|string',
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
