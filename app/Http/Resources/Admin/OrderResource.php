<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $obj = self::toObject($this);
        return $obj;
    }
    public function toObject($obj, $lang = 'en')
    {
        // dd();
        return [
            'id' => $obj->id,
            'customer_id' => $obj->customer_id == 'walk-in-customer' ? 0 : $obj->customer_id,
            'customer' => $obj->customer ? $obj->customer->name : 'walk-in-customer',
            'customer_phone' => $obj->customer ? $obj->customer->phone : 'no contact number',
            'customer_email' => $obj->customer ? $obj->customer->email : 'no email',
            'role' => $obj->customer->role->name ?? '',
            'order_number' => $obj->order_number,
            'type' => ucwords(Str::replace(['_', '-'], ' ', $obj->type)),
            'status' => ucwords(Str::replace(['_', '-'], ' ', $obj->status)),
            'notes' => $obj->notes,
            'total_price' => $obj->total_price,
            'discount' => $obj->discount,
            'created_at' => $obj->created_at,
            'table' => $obj->table ? $obj->table->name : 'no booked',
            'payment_method' => $obj->payment_method ?? 'cash',
            'order_type' => $obj->order_type ?? null,
            'delivery_address' => $obj->delivery_address ?? null,
            'restaurant' => $obj->restaurant ? $obj->restaurant : 'no restaurant' ,
            'phone' => $obj->phone ? $obj->phone : '',
            'dial_code' => $obj->dial_code ? $obj->dial_code : '',
            'coupon_code' => $obj->coupon_code,
            'discount_value' => $obj->discount_value,
            'final_total' => $obj->final_total,
            'products' => $obj->orderProducts ? $obj->orderProducts->map(function ($orderProduct) {
                $image = $orderProduct->product ? Helper::returnFullImageUrl($orderProduct->product->image) : null;
                return [
                    'product_id' => $orderProduct->product_id,
                    'product_name' => $orderProduct->product->name ?? null,
                    'product_price' => $orderProduct->product->price ?? null,
                    'product_discount' => $orderProduct->product->discount ?? null,
                    'product_status' => $orderProduct->product->status ?? null,
                    'product_image' => $image,
                    'quantity' => $orderProduct->quantity,
                    'price' => $orderProduct->price,
                    'variation' => $orderProduct->variation ?? [],
                    "meta_key" => $orderProduct->productProp->meta_key ?? null,
                    "meta_value" => $orderProduct->productProp->meta_value ?? null,
                    "meta_key_type" => $orderProduct->productProp->meta_key_type ?? null,
                ];
            }) : [],

        ];
    }
}
