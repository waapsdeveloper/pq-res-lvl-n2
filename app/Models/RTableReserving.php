<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTableReserving extends Model
{
    protected $table = 'rtable_reserving';

    protected $fillable = [
        "restaurant",
        "rtable_id",
        "booking_start",
        "booking_end",
        "floor",
        "status",
        "description"
    ];
}
