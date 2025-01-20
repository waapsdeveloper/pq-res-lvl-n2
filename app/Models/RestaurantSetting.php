<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    protected $table = 'restaurant_settings';
    protected $fillable = [
        'restaurant_id',
        'meta_key',
        'meta_value',
    ];
    protected $casts = [
        'meta_value',
    ];
    public function timings()
    {
        return $this->hasMany(RestaurantTiming::class, 'restaurant_id', 'id');
    }
    public function settings()
    {
        return $this->hasOne(RestaurantSetting::class, 'restaurant_id', 'id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
