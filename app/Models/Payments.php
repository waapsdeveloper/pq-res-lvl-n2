<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';
    public $timestamps = false; // for fake entries when done remove this line
    protected $fillable = [
        'order_id',
        'amount',
        'customer_id',
        'payment_status',
        'payment_mode',
        'created_at', // for fake entries when done remove this line
    ];
}
