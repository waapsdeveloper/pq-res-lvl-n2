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
        // dd($obj->orderProducts);
        return [
            'id' => $obj->id,
            'customer_id' => $obj->customer_id,
            'customer' => $obj->customer,
            'order_number' => $obj->order_number,
            'status' => $obj->status,
            'total_price' => $obj->total_price,
            'discount' => $obj->discount,
            'created_at' => $obj->created_at->toDateTimeString(),
            'products' => $obj->orderProducts->map(function ($orderProduct) {
                return [
                    'product_id' => $orderProduct->product_id,
                    'product_name' => $orderProduct->product->name,
                    'product_price' => $orderProduct->product->price,
                    'product_discount' => $orderProduct->product->discount,
                    'product_status' => $orderProduct->product->status,
                    'product_image' => Helper::returnFullImageUrl($orderProduct->product->image),
                    'quantity' => $orderProduct->quantity,
                    'price' => $orderProduct->price,
                    'name' => $orderProduct->name,
                ];
            }),
        ];

        // return [
        //     'id' => $obj->id,
        //     'customer_name' => $obj->customer_name,
        //     'customer_phone' => $obj->customer_phone,
        //     'order_number' => $obj->order_number,
        //     'status' => $obj->status,
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
