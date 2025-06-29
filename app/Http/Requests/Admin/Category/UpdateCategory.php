<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategory extends FormRequest
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
            'category_id' => 'nullable|integer|exists:categories,id', // Ensure category is valid
            'restaurant_id' => 'nullable|integer|exists:restaurants,id', // Ensure category is valid
            'status' => 'required|string|in:active,inactive', // Validate status
            'description' => 'nullable|string|max:255', // Validate description
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
