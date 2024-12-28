<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUser extends FormRequest
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
            'name' => 'nullable|string|min:3|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $this->route('id'),
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive',
            // 'address' => 'required|string',
            // 'city' => 'nullable|string',
            // 'state' => 'nullable|string',
            // 'country' => 'nullable|string',
            "userDetails" => 'nullable|array',
            'image' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(jpeg|png|jpg|gif|bmp|svg+xml|webp|tiff);base64,/', $value)) {
                        $fail('The ' . $attribute . ' field must be a valid base64 encoded image.');
                    }
                },
            ],
            'restaurant_id' => 'nullable|integer',

        ];
    }

    /**
     * Customize the error response if validation fails.
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
