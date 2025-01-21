<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    public $timestamps = false; // for fake entries when done remove this line
    protected $fillable = [
        'identifier',
        'order_number',
        'type',
        'status',
        'notes',
        'customer_id',
        'invoice_no',
        'table_no',
        'restaurant_id',
        'total_price',
        'variation',
        'created_at', // for fake entries when done remove this line
    ];

    // Define relationship with OrderProduct
    // public function orderProducts()
    // {
    //     return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    // }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }



    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id')
            ->where(function ($query) {
                $query->where('role_id', 0)
                    ->orWhereNull('role_id');
            });
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rtable()
    {
        return $this->belongsTo(Restaurant::class, 'rtable_id', 'id');
    }
    public function table_no()
    {
        return $this->belongsTo(Rtable::class, 'table_no');
    }
    public function table()
    {
        return $this->belongsTo(Rtable::class, 'table_no', 'id');
    }
}
