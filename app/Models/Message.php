<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'restaurant_id',
        'reply_by_user_id'
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'reply_by_user_id');
    }
}
