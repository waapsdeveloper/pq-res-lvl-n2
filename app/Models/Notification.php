<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    // Fillable attributes (columns you want to allow for mass assignment)
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    // Cast the 'data' field as an array (it will be stored as JSON in the database)
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
