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
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
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

    public static function superAdmin(int $roleId = 1)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function admin(int $roleId = 2)
    {
        return self::where('role_id', $roleId)->get();
    }

    public static function manager(int $roleId = 3)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function chef(int $roleId = 4)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function waiter(int $roleId = 5)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function cashier(int $roleId = 6)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function deliveryBoy(int $roleId = 7)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function customer(int $roleId = 8)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function cleaner(int $roleId = 9)
    {
        return self::where('role_id', $roleId)->get();
    }
    public static function receptionist(int $roleId = 10)
    {
        return self::where('role_id', $roleId)->get();
    }
    public function userDetails()
    {
        return $this->hasMany(UserAddresses::class, 'user_id');
    }
}
