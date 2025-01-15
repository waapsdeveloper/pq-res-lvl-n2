<?php

namespace App\Traits\Traits\Frontend;

use App\Helpers\ServiceResponse;
use App\Models\Rtable;

trait TableBookingTrait
{
    public function tableIdentifier($rtableIdf)
    {

        if (!empty($rtableIdf)) {
            $identifier = $rtableIdf;
            $restaurant = Rtable::where('identifier', $identifier)->first();
            if (!$restaurant) {
                return ServiceResponse::error("Invalid table identifier.", [], 400);
            }
            $restaurant = $restaurant->id;
        }
        return $restaurant;
    }
}
