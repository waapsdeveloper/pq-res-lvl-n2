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
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'image' => 'nullable|string',
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
