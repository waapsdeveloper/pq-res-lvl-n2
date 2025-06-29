<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProduct extends FormRequest
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
        $rules = [
            'name' => 'required|string|min:3|max:255',
            'identifier' => 'nullable|unique:products,identifier,' . $this->id,  // Ensure role is provided
            'restaurant_id' => 'nullable|integer|exists:restaurants,id', // Ensure role is provided
            'category_id' => 'nullable|integer|exists:categories,id', // Ensure role is provided
            'description' => 'nullable|string', // Ensure role is provided
            'price' => 'required', // Ensure role is provided
            'status' => 'required|string|in:active,inactive',
            'notes' => "nullable|string",
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    // Check if it's a base64 image
                    if (strpos($value, 'data:image') === 0) {
                        if (!preg_match('/^data:image\/(jpeg|png|jpg|gif|bmp|svg+xml|webp|tiff);base64,/', $value)) {
                            $fail('The ' . $attribute . ' field must be a valid base64 encoded image.');
                        }
                    } 
                    // Check if it's a URL
                    elseif (filter_var($value, FILTER_VALIDATE_URL) || strpos($value, 'images/') === 0) {
                        // Valid URL or relative path
                        return;
                    }
                    // If neither base64 nor URL, it's invalid
                    else {
                        $fail('The ' . $attribute . ' field must be a valid base64 encoded image or a valid URL.');
                    }
                },
            ],
            'discount' => 'nullable',
            'variation' => 'nullable',
        ];



        return $rules;
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
