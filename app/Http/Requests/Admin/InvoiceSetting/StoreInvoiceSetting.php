<?php

namespace App\Http\Requests\Admin\InvoiceSetting;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceSetting extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or add your auth logic
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'invoice_logo' => 'nullable|string', // base64 string
            'size' => 'nullable|string|max:50',
            'left_margin' => 'nullable|integer|min:0',
            'right_margin' => 'nullable|integer|min:0',
            'google_review_barcode' => 'nullable|string', // base64 string
            'footer_text' => 'nullable|string|max:1000',
            'restaurant_address' => 'nullable|string|max:255',
            'font_size' => 'nullable|integer|min:6|max:72',
        ];
    }
}
