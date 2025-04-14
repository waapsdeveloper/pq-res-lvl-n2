<?php

namespace App\Http\Requests\Admin\RTablebooking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class StoreRTablesBooking extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function prepareForValidation()
    {
        if ($this->has('booking_start')) {
            $this->merge([
                'booking_start' => $this->formatDateTime($this->booking_start),
            ]);
        }

        if ($this->has('booking_end')) {
            $this->merge([
                'booking_end' => $this->formatDateTime($this->booking_end),
            ]);
        }
    }


    private function formatDateTime($value)
    {
        if ($value instanceof Carbon) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_string($value)) {
            // Try to parse automatically if no strict format needed
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        }

        return $value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:users,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'order_id' => 'nullable|integer|exists:orders,id',
            'booking_start' => 'required|date|after_or_equal:now',
            'booking_end' => 'required|date|after:booking_start',
            'no_of_seats' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,checked_in,checked_out,no_show,reserved,completed',
            'rtable_id' => 'nullable|array', // Ensures the field is an array
            'rtable_id.*' => 'integer|exists:rtables,id',
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
