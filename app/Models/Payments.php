<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id',
        'amount',
        'customer_id',
        'status',
        'mode',
    ];
}
