<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Helper;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'footer_text' => $this->footer_text,
            'size' => $this->size,
            'font_size' => $this->font_size,
            'left_margin' => $this->left_margin,
            'right_margin' => $this->right_margin,
            'restaurant_address' => $this->restaurant_address,

            // Convert relative paths to full URLs
            'invoice_logo' => $this->invoice_logo ? Helper::returnFullImageUrl($this->invoice_logo) : null,
            'google_review_barcode' => $this->google_review_barcode 
                                       ? Helper::returnFullImageUrl($this->google_review_barcode) 
                                       : null,

            // Optionally return base64 preview
            'invoice_logo_base64' => $this->invoice_logo ? Helper::returnBase64ImageUrl($this->invoice_logo) : null,
            'google_review_barcode_base64' => $this->google_review_barcode 
                                              ? Helper::returnBase64ImageUrl($this->google_review_barcode) 
                                              : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
