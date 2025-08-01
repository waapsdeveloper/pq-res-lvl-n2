<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['country', 'currency_code', 'currency_name', 'dial_code', 'flag','currency_symbol'];
}
