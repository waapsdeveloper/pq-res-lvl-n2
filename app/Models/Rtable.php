<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtable extends Model
{
    use HasFactory;

    // Specify the name of the table (optional if you follow naming conventions)
    protected $table = 'rtables';

    // Define the primary key (optional, as Laravel uses 'id' by default)
    protected $primaryKey = 'id';

    // Define the columns that can be mass-assigned (fillable)
    protected $fillable = [
        'restaurant_id',
        'identifier',
        'status',
        'description',
    ];

    // If you need to disable timestamps (created_at, updated_at), you can set this to false:
    // public $timestamps = false;

    // Optionally, you can define relationships here, such as with the Restaurant model
    // Example:
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
