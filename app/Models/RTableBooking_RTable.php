<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTableBooking_RTable extends Model
{
    protected $table = 'rtableBooking_rtables';
    protected $fillable = [
        'restaurant_id',
        'rtable_id',
        'rtable_booking_id',
        'booking_start',
        'booking_end',
    ];
    protected $casts = [
        'rtable_id' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class)->with('timings');
    }

    public function rtable()
    {
        return $this->belongsTo(Rtable::class);
    }
    public function rtableBooking()
    {
        return $this->belongsTo(RTablesBooking::class);
    }
}
