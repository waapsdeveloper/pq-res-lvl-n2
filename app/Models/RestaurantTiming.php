<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTiming extends Model
{
    protected $table = 'restaurant_timings';
    protected $fillable = [
        'restaurant_id',
        'day',
        'start_time',
        'end_time',
        'status'
    ];

    public function restaurantDetail()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
