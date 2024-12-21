<?php

namespace App\Http\Requests\Frontend\TableBooking;

use App\Helpers\DateHelper;
use App\Models\RTablesBooking;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTableBooking extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ensure authorization logic is implemented as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $booking = $this->route('id') ? RTablesBooking::find($this->route('id')) : null;

        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'no_of_seats' => 'required|integer|min:2',
            'tables' => 'required|array',
            'tables.*' => 'exists:rtables,id',
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) use ($booking) {
                    $startTime = DateHelper::parseDate($value);
                    if (!$startTime) {
                        $fail("The :attribute is not in a valid format. Accepted formats are: d-m-Y H:i:s, Y-m-d H:i:s, m/d/Y H:i, d-m-y H:i.");
                        return;
                    }
                    if ($booking && $startTime->lessThanOrEqualTo(DateHelper::parseDate($booking->booking_start))) {
                        $fail("The :attribute cannot be earlier than the previous start time.");
                    }
                },
            ],
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'payment_status' => 'nullable|string',
        ];
    }
}
