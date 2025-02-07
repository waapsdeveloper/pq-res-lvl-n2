<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'phone', 'password', 'status', 'role_id', 'restaurant_id', 'address', 'city', 'state', 'country', 'image'];

    protected $hidden = ['password', 'remember_token'];

    // public function addresses()
    // {
    //     return $this->hasMany(CustomerAddresses::class);
    // }

}
