<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurant extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if the table name matches the plural of the model name)
    protected $table = 'restaurants';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Set timestamps to false if the table doesn't have created_at and updated_at columns
    public $timestamps = true;

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'logo',
        'rating',
        'image',
        'phone',
        'website',
        'address',
        'email',
        'description',
        'favicon',
        'copyright_text',
        'status',
        'image'
    ];

    // Attributes that should be hidden from arrays (e.g., sensitive data)
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Cast attributes to a specific type
    protected $casts = [
        'rating' => 'float',
    ];
    public function timings()
    {
        return $this->hasMany(RestaurantTiming::class);
    }
    public function settings()
    {
        return $this->hasOne(RestaurantTiming::class);
    }
    public function rTables()
    {
        return $this->hasMany(Rtable::class);
    }

    // Relationships

    // // Example: A restaurant has many reviews
    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

    // // Example: A restaurant belongs to a city
    // public function city()
    // {
    //     return $this->belongsTo(City::class);
    // }

    // // Example: A restaurant can have many tags (many-to-many relationship)
    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class, 'restaurant_tag', 'restaurant_id', 'tag_id');
    // }
}
