<?php

namespace App\Traits\Traits\Frontend;

use App\Helpers\ServiceResponse;
use App\Models\Restaurant;
use App\Models\Rtable;

trait TableBookingTrait
{
    public function tableIdentifier($rtableIdf)
    {
        if (empty($rtableIdf)) {
            return ServiceResponse::error("Table identifier is required.", [], 400);
        }
        $rtable = Rtable::where('identifier', $rtableIdf)->first();
        if (!$rtable) {
            return ServiceResponse::error("Invalid table identifier.", [], 400);
        }
        $restaurant = Restaurant::find($rtable->restaurant_id);
        if (!$restaurant) {
            return ServiceResponse::error("Restaurant not found for the given table identifier.", [], 404);
        }
        return  $restaurant->id;
    }
}
