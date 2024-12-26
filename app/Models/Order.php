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
        'restaurant_id'
    ];

    // Define relationship with OrderProduct
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }
}
