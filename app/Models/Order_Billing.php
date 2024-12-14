<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_Billing extends Model
{
    protected $table = 'order_billing';
    protected $fillable = [
        'order_id',
        'amount',
        'tax',
        'total',
        'status',
        'discount',
        'total_price',
    ];
}
