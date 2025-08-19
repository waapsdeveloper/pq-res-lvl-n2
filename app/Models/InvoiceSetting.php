<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'invoice_logo',
        'size',
        'left_margin',
        'right_margin',
        'google_review_barcode',
        'footer_text',
        'restaurant_address',
        'font_size',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
