<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'created_at' => $this->created_at,
            'products' => $this->orderProducts->map(function ($orderProduct) {
                return [
                    'product_id' => $orderProduct->product_id,
                    'quantity' => $orderProduct->quantity,
                    'price' => $orderProduct->price,
                    'product_name' => $orderProduct->product->name,
                ];
            }),
        ];
    }
}
