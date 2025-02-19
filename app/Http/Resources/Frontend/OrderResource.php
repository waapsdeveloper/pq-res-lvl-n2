<?php

namespace App\Http\Resources\Frontend;

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
            'order_number' => $obj->order_number,
            'restaurant' => $obj->restaurant,
            'type' => ucwords(Str::replace(['_', '-'], ' ', $obj->type)),
            'status' => ucwords(Str::replace(['_', '-'], ' ', $obj->status)),
            'total_price' => $obj->total_price,
            'discount' => $obj->discount,
            'created_at' => $obj->created_at,
            'table' => $obj->table,
            'payment_method' => $obj->payment_method ?? 'cash',
            'order_type' => $obj->order_type ?? null,
            'delivery_address' => $obj->delivery_address ?? null,
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
                    'variations' => $orderProduct->variation ? json_decode($orderProduct->variation) : [],
                    "meta_key" => $orderProduct->productProp->meta_key ?? null,
                    "meta_value" => $orderProduct->productProp->meta_value ?? null,
                    "meta_key_type" => $orderProduct->productProp->meta_key_type ?? null,
                ];
            }) : [],

        ];
    }
}
