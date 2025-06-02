<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'role_id',
        'slug',
        'level',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
