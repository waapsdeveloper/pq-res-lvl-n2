<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'order_id' => 'sometimes|exists:orders,id',
            'invoice_no' => 'sometimes|string|unique:invoices,invoice_no,' . $this->id,
            'invoice_date' => 'sometimes|date',
            'payment_method' => 'sometimes|string|max:50',
            'total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,paid,cancelled',
        ];
    }
}
