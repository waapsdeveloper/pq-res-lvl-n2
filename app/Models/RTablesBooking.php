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
        'payment_method',
        'payment_status'
    ];

    public function rTableBookings()
    {
        return $this->hasMany(RTableBooking_RTable::class, 'rtable_booking_id');
    }
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
    public function rtablesBookingId()
    {
        return $this->hasMany(RTable::class, 'rtable_booking_id', 'id');
    }
}
