<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Set timestamps to false if the table doesn't have created_at and updated_at columns
    public $timestamps = true;

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'slug'
    ];

    // // Attributes that should be hidden from arrays (e.g., sensitive data)
    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
    // ];

    // Cast attributes to a specific type
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }



}
