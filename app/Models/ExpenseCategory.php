<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'restaurant_id',
        'category_name',
        'daily_estimate',
        'weekly_estimate',
        'monthly_estimate',
        'description',
        'image',
        'status',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
