<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $table = 'variations';
    protected $fillable = [
        'name',
        'description',
        'meta_value'
    ];
    protected $casts = [
        'meta_value' => 'array',
    ];
}
