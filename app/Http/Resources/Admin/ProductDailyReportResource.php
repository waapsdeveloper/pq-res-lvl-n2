<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDailyReportResource extends JsonResource
{
    public function toArray($request)
    {
        // The resource is an associative array, so access it as one.
        return [
            'product_id'    => $this['product_id'],
            'product_name'  => $this['product_name'],
            'category'      => $this['category'],
            'total_quantity'=> $this['total_quantity'],
            'total_sales'   => $this['total_sales'],
            'total_tax'     => $this['total_tax'],
            'total_discount'=> $this['total_discount'],
            'unit_price'    => $this['unit_price_sample'],
            'rows'          => collect($this['rows'])->map(function ($row) {
                return [
                    'order_id'      => $row['order_id'],
                    'order_number'  => $row['order_number'],
                    'order_time'    => $row['order_time'],
                    'variation'     => $row['variation'],
                    'quantity'      => $row['quantity'],
                    'unit_price'    => $row['unit_price'],
                    'total_price'   => $row['total_price'],
                    'tax'           => $row['tax'],
                    'discount'      => $row['discount'],
                ];
            }),
        ];
    }
}