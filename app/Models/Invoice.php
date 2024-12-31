<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'order_id',
        'invoice_no',
        'invoice_date',
        'payment_method',
        'total',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
