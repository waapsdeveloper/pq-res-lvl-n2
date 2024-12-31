<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'identifier',
        'order_number',
        'type',
        'status',
        'notes',
        'customer_id',
        'invoice_no',
        'table_no',
        'restaurant_id',
        'total_price',
        'order_at'
    ];

    // Define relationship with OrderProduct
    // public function orderProducts()
    // {
    //     return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    // }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rtable()
    {
        return $this->belongsTo(Restaurant::class, 'rtable_id', 'id');
    }
}
