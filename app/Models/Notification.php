<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    // Fillable attributes (columns you want to allow for mass assignment)
    protected $keyType = 'string'; // UUID
    public $incrementing = false;
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Define relationships if any (e.g. belongsTo, hasMany)
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }
}
