<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProps extends Model
{
    protected $table = 'product_props';
    protected $fillable = [
        'product_id',
        'meta_key',
        'meta_value',
        'meta_key_type',
    ];
    protected $casts = [
        // 'meta_key' => 'array',
        'meta_value' => 'array',
        // 'meta_key_type' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id', 'product_id');
    }
}
