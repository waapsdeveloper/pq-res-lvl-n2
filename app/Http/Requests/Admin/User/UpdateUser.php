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
            'name' => 'nullable|string|min:3|max:255', // Optional, must be a string with length constraints
            'email' => 'nullable|email|max:255|unique:users,email,' . $this->route('id'), // Unique email, excluding current user's email
            'phone' => 'nullable|string|max:15', // Optional, with a max length
            'password' => 'nullable|string|min:6', // Optional, with a minimum length
            'role' => 'nullable|integer|in:2,3,4,5', // Optional, valid roles
            'status' => 'nullable|string|in:active,inactive', // Optional, must be 'active' or 'inactive'
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image, size and type constraints
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
