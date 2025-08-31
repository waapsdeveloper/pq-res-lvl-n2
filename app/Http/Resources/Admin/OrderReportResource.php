<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_no'  => $this->order_number,
            'time'      => $this->created_at->format('Y-m-d H:i:s'),
            'type'      => ucfirst($this->order_type),
            'status'    => ucfirst($this->status),
            'tax'       => $this->tax_amount ?? 0,
            'amount'    => $this->total_price ?? 0,
            'discount'  => $this->discount_value ?? 0,
            'tips'      => $this->tips_amount ?? 0,
            'source'    => $this->source,
            'payment'   => $this->payment_method,
            'total'     => $this->final_total ?? 0,
            // safe trashed check: returns true if model uses SoftDeletes and is soft-deleted
            'isDeleted' => method_exists($this->resource, 'trashed') ? (bool) $this->resource->trashed() : false,
        ];
    }
}
