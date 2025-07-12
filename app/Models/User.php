<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        'dial_code',
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

    /**
     * Accessor for the `image` attribute.
     * Transforms the image path into an S3 pre-signed URL.
     */
    public function getImageAttribute($value)
    {
        // Use the Helper to generate a pre-signed URL
        return $value ? Helper::returnFullImageUrl($value) : null;
    }

    /**
     * Override the default password reset notification to use a custom mailable.
     */
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(config('app.url') . route('password.reset', ['token' => $token, 'email' => $this->email], false));
        $activeRestaurant = \App\Helpers\Helper::getActiveRestaurantId();
        $restaurantLogo = $activeRestaurant ? $activeRestaurant->logo : null;
        $restaurantName = $activeRestaurant ? $activeRestaurant->name : config('app.name');

        Mail::send('mail.forgot_password', [
            'resetUrl' => $resetUrl,
            'restaurantLogoCid' => 'restaurant_logo_cid',
            'restaurantName' => $restaurantName
        ], function ($message) use ($restaurantLogo) {
            $message->to($this->email);
            $message->subject('Reset your password');
            $logoEmbedded = false;
            $logoPath = null;
            if ($restaurantLogo) {
                // If the logo is a full URL, extract the path after /storage/
                if (strpos($restaurantLogo, '/storage/') !== false) {
                    $logoPath = substr($restaurantLogo, strpos($restaurantLogo, '/storage/') + strlen('/storage/'));
                } else {
                    $logoPath = ltrim($restaurantLogo, '/');
                }
                $logoFullPath = storage_path('app/public/' . $logoPath);
                Log::info('Logo path for email: ' . $logoFullPath);
                if (file_exists($logoFullPath)) {
                    $message->embed($logoFullPath, 'restaurant_logo_cid');
                    $logoEmbedded = true;
                } else {
                    // Try to get the full image URL and download it
                    $logoUrl = \App\Helpers\Helper::returnFullImageUrl($restaurantLogo);
                    if ($logoUrl) {
                        $tmpFile = tempnam(sys_get_temp_dir(), 'logo_');
                        try {
                            file_put_contents($tmpFile, file_get_contents($logoUrl));
                            if (file_exists($tmpFile)) {
                                $message->embed($tmpFile, 'restaurant_logo_cid');
                                $logoEmbedded = true;
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to download logo for email: ' . $e->getMessage());
                        } finally {
                            if (file_exists($tmpFile)) {
                                @unlink($tmpFile);
                            }
                        }
                    }
                }
            }
            if (!$logoEmbedded) {
                $defaultLogoPath = public_path('default-logo.png');
                if (file_exists($defaultLogoPath)) {
                    $message->embed($defaultLogoPath, 'restaurant_logo_cid');
                }
            }
        });
    }

    // role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function userDetail()
    {
        return $this->hasOne(UserAddresses::class, 'user_id', 'id');
    }
    public function orders()
    {
        return $this->belongsTo(Order::class, 'id', 'customer_id');
    }
    public function profile()
    {
        return $this->hasOne(Profiles::class, 'user_id', 'id');
    }
    public function profiles()
    {
        return $this->hasMany(Profiles::class, 'user_id', 'id');
    }
}
