<?php

namespace App\Http\Requests\Admin\Rtable;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRtable extends FormRequest
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
        $rtableId = $this->route('id'); // Get the current Rtable ID from the route

        return [
            'restaurant' => 'nullable|integer|exists:restaurants,id', // Ensure the restaurant exists
            'identifier' => 'nullable|string|min:3|max:255|unique:rtables,identifier,' . $rtableId, // Unique identifier excluding the current record
            'no_of_seats' => 'required|integer|max:255', // Table location
            'description' => 'nullable|string|max:500', // Table description (nullable)
            'floor' => 'nullable|string|max:500', // Table description (nullable)
            'status' => 'nullable', // Table description (nullable) // Optional description
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
