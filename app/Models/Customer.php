<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customers';
    protected $fillable = [

    ];



    public function addresses()
    {
        return $this->hasMany(CustomerAddresses::class);
    }

}
