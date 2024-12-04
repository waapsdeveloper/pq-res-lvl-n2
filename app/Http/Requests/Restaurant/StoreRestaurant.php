<?php
namespace App\Http\Requests\Restaurant;

use App\Helpers\ServiceResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurant extends FormRequest
{
    public function authorize()
    {
        return true; // Allowing all users for now
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string',  // phone is optional
            'email' => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',  // valid email pattern
            'website' => ['nullable', 'regex:/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/'],  // website validation
            'description' => 'nullable|string|max:1000',  // description is optional
            'status' => 'nullable|string',  // status is optional
            'rating' => 'nullable|numeric|min:0|max:5',  // rating is optional, but must be between 0 and 5
            'opening_hours' => 'nullable|json',  // opening_hours is optional but must be a valid JSON if provided
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Restaurant name is mandatory. Please provide a name for the restaurant.',
            'name.string' => 'The name should be a valid string, containing only letters, numbers, and spaces.',
            'name.min' => 'The name must be at least 3 characters long.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.email' => 'Please provide a valid email address (e.g., example@domain.com).',
            'email.regex' => 'The email format is incorrect. Ensure it follows the pattern like example@domain.com.',
            'phone.string' => 'Phone number should be a valid string.',
            'opening_hours.json' => 'Opening hours must be a valid JSON string.',
            'rating.min' => 'Rating must be between 0 and 5.',
            'rating.max' => 'Rating cannot exceed 5.',
        ];
    }


    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        $errors = $validator->errors()->all();


        return ServiceResponse::error('Validation failed', ['errors' => $errors]);
    }
}
