<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id',
        'address_line',
        'city',
        'state',
        'country'
    ];
}
