<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddresses extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
