<?php

namespace App\Traits\Traits\Frontend;

use App\Helpers\ServiceResponse;
use App\Models\Restaurant;
use App\Models\Rtable;

trait TableBookingTrait
{
    public function tableIdentifier($rtableIdf)
    {
        if (!empty($rtableIdf)) {
            $idf = Rtable::where('identifier', $rtableIdf)->first();
            if (!$idf) {
                return ServiceResponse::error("Invalid table identifier.", [], 400);
            }

            $restaurant = Restaurant::find($idf->restaurant_id);
        }
        return $restaurant;
    }
}
