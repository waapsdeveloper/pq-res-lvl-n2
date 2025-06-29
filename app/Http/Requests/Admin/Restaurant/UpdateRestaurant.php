<?php

namespace App\Http\Requests\Admin\Restaurant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRestaurant extends FormRequest
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
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string', // |regex:/^[0-9]{10,15}$/
            'email' => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'website' => ['nullable', 'regex:/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/'],
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string',
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(jpeg|png|jpg|gif|bmp|svg+xml|webp|tiff);base64,/', $value)) {
                        $fail('The ' . $attribute . ' field must be a valid base64 encoded image.');
                    }
                },
            ], // Base64 string validation
            'favicon' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(jpeg|png|jpg|gif|bmp|svg+xml|webp|tiff);base64,/', $value)) {
                        $fail('The ' . $attribute . ' field must be a valid base64 encoded image.');
                    }
                },
            ], // Base64 string validation
            'logo' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(jpeg|png|jpg|gif|bmp|svg+xml|webp|tiff);base64,/', $value)) {
                        $fail('The ' . $attribute . ' field must be a valid base64 encoded image.');
                    }
                },
            ],
            'copyright_text' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'timings' => 'nullable|array',
            'timings.*.key' => 'required|string',
            'timings.*.value' => 'nullable',
            'meta' => 'nullable|array',
            'meta.*.key' => 'required|string',
            'meta.*.value' => 'nullable|string',
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
