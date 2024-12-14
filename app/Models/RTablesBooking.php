<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTablesBooking extends Model
{
    protected $table = 'rtables_booking';
    protected $fillable = [
        'rtable_id',
        'customer_id',
        'order_id',
        'booking_start',
        'booking_end',
        'number_of_people',
        'status',
    ];
}
