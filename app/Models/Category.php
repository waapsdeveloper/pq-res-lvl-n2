<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
        'restaurant_id',
        'description',
        'image',
        'status',
        'identifier',
    ];

    // Define the relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function productsCount() {
        return $this->products()->count();
    }
}
