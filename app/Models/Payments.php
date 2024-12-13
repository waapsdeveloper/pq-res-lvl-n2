<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id',
        'amount', // this is equals to total of order_billing table 
        'amount_user_paid',
        'amount_return_to_user',
        'status',
        'customer_id',
        'mode'
    ];
}
