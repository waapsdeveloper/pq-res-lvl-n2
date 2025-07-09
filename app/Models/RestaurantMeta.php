<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantMeta extends Model
{
    use HasFactory;

    protected $table = 'restaurant_meta';

    protected $fillable = [
        'restaurant_id',
        'meta_key',
        'meta_value',
    ];

    protected $casts = [
        'meta_value' => 'string',
    ];

    /**
     * Get the restaurant that owns the meta.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Scope to get meta by key
     */
    public function scopeByKey($query, $key)
    {
        return $query->where('meta_key', $key);
    }

    /**
     * Get meta value by key for a specific restaurant
     */
    public static function getMetaValue($restaurantId, $key, $default = null)
    {
        $meta = self::where('restaurant_id', $restaurantId)
                    ->where('meta_key', $key)
                    ->first();
        
        return $meta ? $meta->meta_value : $default;
    }

    /**
     * Set meta value for a restaurant
     */
    public static function setMetaValue($restaurantId, $key, $value)
    {
        return self::updateOrCreate(
            [
                'restaurant_id' => $restaurantId,
                'meta_key' => $key,
            ],
            [
                'meta_value' => $value,
            ]
        );
    }
} 