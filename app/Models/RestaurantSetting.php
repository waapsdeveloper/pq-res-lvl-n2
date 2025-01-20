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
}
