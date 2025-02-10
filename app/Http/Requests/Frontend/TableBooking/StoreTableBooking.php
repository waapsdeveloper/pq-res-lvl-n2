<?php

namespace App\Http\Requests\Frontend\TableBooking;

use App\Helpers\DateHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Carbon\Carbon;

class StoreTableBooking extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'no_of_seats' => 'required|integer|min:2',
            'name' => 'required|string',
            'phone' => 'required|string',
            'tables' => 'required|array',
            'tables.*' => 'exists:rtables,id',
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) {

                    $startTime = Carbon::parse($value);
                    if (!$startTime) {
                        $fail("The :attribute is not in a valid format. Accepted formats are: d-m-Y H:i:s, Y-m-d H:i:s, m/d/Y H:i, d-m-y H:i.");
                        return;
                    }
                    if ($startTime->lt(Carbon::now()->addMinutes(30))) {
                        $fail("The :attribute must be at least 30 minutes ahead of the current time.");
                    }

                },
            ],
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'status' => 'nullable',
            'payment_method' => 'nullable|string',
            'payment_status' => 'nullable|string',

        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'bool' => false,
            'status' => 422,
            'message' => $validator->errors()->first(),
            'result' => null,
        ], 422));
    }
}
