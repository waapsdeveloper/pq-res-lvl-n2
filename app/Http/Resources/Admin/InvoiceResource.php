<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $obj = self::toObject($this);
        return $obj;
    }

    public static function toObject($obj, $lang = 'en')
    {
        return [
            "id" => $obj->id,
            'order_number' => $obj->order->order_number,
            'order_products' => $obj->order ? $obj->order->orderProducts : "",
            'customer' => $obj->order->customer,
            "invoice_no" => $obj->invoice_no,
            "invoice_date" => $obj->invoice_date,
            "payment_method" => ucwords($obj->payment_method),
            "payment_status" => $obj->payment_status,
            "total" => $obj->total,
            "total_price" => $obj->total,
            "status" => ucfirst($obj->status),
            "notes" => $obj->notes,
        ];
    }
}
