<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    protected $table = 'order_products';
    public $timestamps = false; // for fake entries when done remove this line
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'notes',
        'category',
        'variation',
        'created_at', // for fake entries when done remove this line

    ];

    protected $casts = [
        'variation' => 'array'
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function productProp()
    {
        return $this->belongsTo(ProductProps::class, 'product_id', 'product_id'); // Assuming 'product_id' is the foreign key in both OrderProduct and ProductProp tables
    }
}
