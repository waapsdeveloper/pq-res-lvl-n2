<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $table = 'products';
    use SoftDeletes;
     protected $dates = ['deleted_at'];
    protected $fillable = [
        'category_id',
        'restaurant_id',
        'identifier',
        'name',
        'description',
        'price',
        'currency',
        'discount',
        'image',
        'status',
        // 'variation_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function productProps()
    {
        return $this->hasMany(ProductProps::class, 'product_id'); // Assuming 'product_id' is the foreign key
    }
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
