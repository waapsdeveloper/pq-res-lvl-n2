<?php

namespace App\Http\Requests\Admin\RestaurantTiming;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRestaurantTiming extends FormRequest
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
            'restaurant_id' => 'sometimes|integer|exists:restaurants,id',
            'timings' => 'sometimes|array',
            'timings.*.key' => 'required|string',
            'timings.*.value' => 'nullable',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            $timings = $data['timings'] ?? [];
            
            foreach ($timings as $timing) {
                $key = $timing['key'] ?? '';
                $value = $timing['value'] ?? null;
                
                // Validate time format for time-related keys
                if (str_contains($key, '_start_time') || str_contains($key, '_end_time') || 
                    str_contains($key, '_break_start') || str_contains($key, '_break_end')) {
                    if ($value && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
                        $validator->errors()->add('timings', "Invalid time format for {$key}. Use HH:MM format.");
                    }
                }
                
                // Validate boolean values
                if (str_contains($key, '_is_24_hours') || str_contains($key, '_is_off')) {
                    if ($value !== null && !in_array($value, [true, false, 'true', 'false', 1, 0, '1', '0'])) {
                        $validator->errors()->add('timings', "Invalid boolean value for {$key}.");
                    }
                }
                
                // Validate array values for off_days
                if ($key === 'off_days' && $value !== null) {
                    if (!is_array($value) && !is_string($value)) {
                        $validator->errors()->add('timings', "off_days must be an array or JSON string.");
                    }
                }
            }
        });
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
