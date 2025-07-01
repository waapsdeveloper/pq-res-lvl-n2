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
            'timing' => 'sometimes|array',
            'timing.global' => 'sometimes|array',
            'timing.global.start_time' => 'nullable|date_format:H:i',
            'timing.global.end_time' => 'nullable|date_format:H:i',
            'timing.global.day_type' => 'nullable|string|in:week_days,weekends,all',
            'timing.global.is_24h' => 'nullable|boolean',
            'timing.global.break_times' => 'nullable|array',
            'timing.global.break_times.*.start' => 'required_with:timing.global.break_times|date_format:H:i',
            'timing.global.break_times.*.end' => 'required_with:timing.global.break_times|date_format:H:i',
            'timing.days' => 'sometimes|array',
            'timing.days.*.day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'timing.days.*.start_time' => 'required|date_format:H:i',
            'timing.days.*.end_time' => 'required|date_format:H:i',
            'timing.days.*.status' => 'required|string|in:active,inactive',
            'timing.days.*.is_24h' => 'required|boolean',
            'timing.days.*.is_open' => 'required|boolean',
            'timing.days.*.is_off_day' => 'required|boolean',
            'timing.days.*.break_times' => 'nullable|array',
            'timing.days.*.break_times.*.start' => 'required_with:timing.days.*.break_times|date_format:H:i',
            'timing.days.*.break_times.*.end' => 'required_with:timing.days.*.break_times|date_format:H:i',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            $timing = $data['timing'] ?? [];
            
            // Validate global settings
            if (isset($timing['global'])) {
                $global = $timing['global'];
                
                // Validate global times
                if (isset($global['start_time']) && isset($global['end_time'])) {
                    if ($global['start_time'] >= $global['end_time']) {
                        $validator->errors()->add('timing.global', 'Global end time must be after start time.');
                    }
                }
                
                // Validate global break times
                if (isset($global['break_times']) && is_array($global['break_times'])) {
                    foreach ($global['break_times'] as $index => $break) {
                        if (isset($break['start']) && isset($break['end'])) {
                            if ($break['start'] >= $break['end']) {
                                $validator->errors()->add('timing.global.break_times', "Break time {$index}: end time must be after start time.");
                            }
                        }
                    }
                }
            }
            
            // Validate days settings
            if (isset($timing['days']) && is_array($timing['days'])) {
                foreach ($timing['days'] as $dayIndex => $dayData) {
                    // Validate day times
                    if (isset($dayData['start_time']) && isset($dayData['end_time'])) {
                        if ($dayData['start_time'] >= $dayData['end_time']) {
                            $validator->errors()->add('timing.days', "Day {$dayData['day']}: end time must be after start time.");
                        }
                    }
                    
                    // Validate day break times
                    if (isset($dayData['break_times']) && is_array($dayData['break_times'])) {
                        foreach ($dayData['break_times'] as $breakIndex => $break) {
                            if (isset($break['start']) && isset($break['end'])) {
                                if ($break['start'] >= $break['end']) {
                                    $validator->errors()->add('timing.days', "Day {$dayData['day']} break time {$breakIndex}: end time must be after start time.");
                                }
                            }
                        }
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
