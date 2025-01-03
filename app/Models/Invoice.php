<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    public $timestamps = false; // for fake entries when done remove this line
    protected $fillable = [
        'order_id',
        'invoice_no',
        'invoice_date',
        'payment_method',
        'total',
        'status',
        'notes',
        'created_at', // for fake entries when done remove this line
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
