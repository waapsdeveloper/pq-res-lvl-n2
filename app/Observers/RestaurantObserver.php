<?php
namespace App\Observers;

use App\Models\Restaurant;
use App\Models\BranchConfig;

class RestaurantObserver
{
    public function created(Restaurant $restaurant)
    {
        // Create a default branch configuration for the new restaurant
        BranchConfig::create([
            'branch_id' => $restaurant->id,
            'tax' => 0, // Default tax value
            'currency' => 'USD', // Default currency
            'dial_code' => '+1', // Default dial code
        ]);
    }
}