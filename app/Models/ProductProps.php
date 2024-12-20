<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProps extends Model
{
    protected $table = 'product_props';
    protected $fillable = [
        'product_id',
        'meta_key',
        'meta_value',
        'meta_key_type',
    ];
}
