<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $fillable = [
        'order_id',
        'event_type',
        'old_value',
        'new_value',
        'performed_by',
        'performed_by_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
