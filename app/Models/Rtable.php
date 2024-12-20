<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtable extends Model
{
    use HasFactory;

    // Specify the name of the table (optional if you follow naming conventions)
    protected $table = 'rtables';

    // Define the primary key (optional, as Laravel uses 'id' by default)
    // protected $primaryKey = 'id';

    // Define the columns that can be mass-assigned (fillable)
    protected $fillable = [
        'restaurant_id',
        'identifier',
        'no_of_seats',
        'description',
        'status',
        'floor'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
    // // Rtable Model
    // public function restaurantTimings()
    // {
    //     return $this->hasMany(RestaurantTimings::class, 'restaurant_id', 'restaurant');
    // }
    public function restaurantDetail()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
    // Rtable Model
    public function restaurantTimings()
    {
        return $this->hasMany(RestaurantTiming::class, 'restaurant_id', 'restaurant_id');
    }
}
