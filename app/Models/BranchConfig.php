<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 
        'country', 
        'tax', 
        'currency', 
        'dial_code', 
        'currency_symbol', 
        'delivery_charges', 
        'tips',
        'enableTax',
        'enableDeliveryCharges'
    ];

    protected $casts = [
        'enableTax' => 'boolean',
        'enableDeliveryCharges' => 'boolean',
        'tax' => 'decimal:2',
        'delivery_charges' => 'decimal:2',
        'tips' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Restaurant::class, 'branch_id');
    }
}
