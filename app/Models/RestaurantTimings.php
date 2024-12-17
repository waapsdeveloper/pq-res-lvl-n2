<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTimings extends Model
{
    protected $table = 'restaurant_timings';
    protected $fillable = [
        'restaurant',
        'day',
        'start_time',
        'end_time',
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
