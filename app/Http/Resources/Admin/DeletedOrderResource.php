<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DeletedOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->toObject($this);
    }

    public function toObject($obj, $lang = 'en')
    {
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
            'updated_at' => $obj->updated_at,
            'table' => $obj->table ? $obj->table->name : 'no booked',
            'payment_method' => $obj->payment_method ?? 'cash',
            'source' => $obj->source,
            'order_type' => $obj->order_type ?? null,
            'delivery_address' => $obj->delivery_address ?? null,
            'restaurant' => $obj->restaurant
                ? array_merge(
                    $obj->restaurant->toArray(),
                    [
                        'logo' => $obj->restaurant->logo ? Helper::returnFullImageUrl($obj->restaurant->logo) : null,
                        'favicon' => $obj->restaurant->favicon ? Helper::returnFullImageUrl($obj->restaurant->favicon) : null,
                        'image' => $obj->restaurant->image ? Helper::returnFullImageUrl($obj->restaurant->image) : null,
                        'logo_base64' => Helper::returnBase64ImageUrl($obj->restaurant->logo ?? null),
                    ]
                )
                : 'no restaurant',
            'phone' => $obj->phone ?? '',
            'dial_code' => $obj->dial_code ?? '',
            'coupon_code' => $obj->coupon_code,
            'is_paid' => (bool) $obj->is_paid,
            'discount_value' => $obj->discount_value,
            'final_total' => $obj->final_total,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'tips' => $this->tips,
            'tips_amount' => $this->tips_amount,
            'delivery_charges' => $this->delivery_charges,
            'isDeleted' => true, // ✅ mark it deleted

            // Products including soft-deleted
            'products' => $obj->orderProducts ? $obj->orderProducts->map(function ($orderProduct) {
                $product = $orderProduct->product()->withTrashed()->first(); // soft-deleted product
                $productProp = $orderProduct->productProp()->withTrashed()->first(); // soft-deleted prop
    
                $image = $product ? Helper::returnFullImageUrl($product->image) : null;

                return [
                    'product_id' => $orderProduct->product_id,
                    'category' => $orderProduct->category,
                    'product_name' => $product->name ?? null,
                    'product_price' => $product->price ?? null,
                    'product_discount' => $product->discount ?? null,
                    'product_status' => $product->status ?? null,
                    'product_image' => $image,
                    'quantity' => $orderProduct->quantity,
                    'price' => $orderProduct->price,
                    'variation' => $orderProduct->variation ? json_decode($orderProduct->variation, true) : [],
                    'meta_key' => $productProp->meta_key ?? null,
                    'meta_key_type' => $productProp->meta_key_type ?? null,
                ];
            }) : [],

        ];
    }
}
