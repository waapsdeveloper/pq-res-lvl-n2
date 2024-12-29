<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'notes',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // Relationship with Product
    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id', 'id');
    // }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
