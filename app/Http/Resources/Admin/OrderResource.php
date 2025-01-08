<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'order_number' => $obj->order_number,
            'type' => $obj->type,
            'status' => ucFirst($obj->status),
            'total_price' => $obj->total_price,
            'discount' => $obj->discount,
            'created_at' => $obj->created_at,
            'table' => $obj->table ? $obj->table->name : 'no booked',
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

        // return [
        //     'id' => $obj->id,
        //     'customer_name' => $obj->customer_name,
        //     'customer_phone' => $obj->customer_phone,
        //     'order_number' => $obj->order_number,
        //     'status' => ucFirst($obj->status),
        //     'total_price' => $obj->total_price,
        //     'discount' => $obj->discount,
        //     'created_at' => $obj->created_at,
        //     'products' => $obj->orderProducts->map(function ($orderProduct) {
        //         return [
        //             'product_id' => $orderProduct->product_id,
        //             'quantity' => $orderProduct->quantity,
        //             'price' => $orderProduct->price,
        //             'product_name' => $orderProduct->product->name,
        //         ];
        //     }),
        // ];
    }
}
