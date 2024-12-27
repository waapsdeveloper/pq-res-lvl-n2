<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
    ];

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

}
