<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\BranchConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/restaurants.json');

        // Read and decode JSON
        $restaurants = json_decode(File::get($jsonFilePath), true);

        // Insert restaurants into the database
        foreach ($restaurants as $data) {
            $restaurant = Restaurant::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'website' => $data['website'],
                'description' => $data['description'],
                'rating' => $data['rating'],
                'image' => $data['image'],
                'favicon' => $data['favicon'],
                'logo' => $data['logo'],
                'status' => $data['status'],
                'is_active' => $data['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create branch config for the restaurant
            BranchConfig::create([
                'branch_id' => $restaurant->id,
                'tax' => $data['tax'] ?? 0, // Default tax to 0 if not provided
                'currency' => $data['currency'] ?? 'USD', // Default currency to USD if not provided
                'dial_code' => $data['dial_code'] ?? '+1', // Default dial code to +1 if not provided
            ]);
        }

        $this->command->info('Restaurants imported successfully from JSON file.');
    }
}
