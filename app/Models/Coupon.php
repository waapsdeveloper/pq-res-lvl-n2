<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_value',
        'discount_type',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        return $this->is_active &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit) &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
