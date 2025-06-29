<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchConfig extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'tax', 'currency', 'dial_code', 'currency_symbol', 'delivery_charges', 'tips'];


    public function branch()
    {
        return $this->belongsTo(Restaurant::class, 'branch_id');
    }
}
