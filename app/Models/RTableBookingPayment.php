<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTableBookingPayment extends Model
{
    protected $table = 'rtable_booking_payment';
    protected $fillable = [
        'rtable_booking_id',
        'payment_method',
        'payment_gateway',
        'payment_col1',
        'payment_col2',
        'payment_status'
    ];

    public function rTableBokking()
    {
        return $this->belongsTo(RTablesBooking::class);
    }
}
