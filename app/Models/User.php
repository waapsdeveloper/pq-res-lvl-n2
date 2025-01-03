<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false; // for fake entries when done remove this line
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'phone',
        'image',
        'restaurant_id',
        'created_at', // for fake entries when done remove this line

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // role
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function userDetail()
    {
        return $this->hasOne(UserAddresses::class, 'user_id', 'id');
    }
    public function orders()
    {
        return $this->belongsTo(Order::class, 'id', 'customer_id');
    }
}
