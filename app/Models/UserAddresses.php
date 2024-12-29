<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
        'country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
