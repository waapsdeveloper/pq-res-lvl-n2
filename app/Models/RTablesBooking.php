<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTablesBooking extends Model
{
    protected $table = 'rtable_bookings';
    protected $fillable = [
        'customer_id',
        'order_id',
        'booking_start',
        'booking_end',
        'no_of_seats',
        'description',
        'status',
    ];
    public function rTable()
    {
        return $this->belongsTo(RTable::class, 'rtable_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
