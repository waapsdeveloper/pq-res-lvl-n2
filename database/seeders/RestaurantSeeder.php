<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\BranchConfig;
use App\Models\InvoiceSetting;
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
            $restaurant = Restaurant::updateOrCreate(
                ['name' => $data['name']],
                [
                    'address' => $data['address'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'email' => $data['email'] ?? null,
                    'description' => $data['description'] ?? null,
                    'image' => $data['image'] ?? null,
                    'favicon' => $data['favicon'] ?? null,
                    'logo' => $data['logo'] ?? null,
                    'status' => $data['status'] ?? 1,
                    'is_active' => $data['is_active'] ?? 1,
                    'updated_at' => now(),
                ]
            );

            // Create branch config for the restaurant
            BranchConfig::updateOrCreate(
                [
                    'branch_id' => $restaurant->id,
                ],
                [
                    'tax' => $data['tax'] ?? 0,
                    'currency' => $data['currency'] ?? 'USD',
                    'dial_code' => $data['dial_code'] ?? '+1',
                    'currency_symbol' => $data['currency_symbol'] ?? '$',
                ]
            );

            // Create invoice settings for the restaurant
           
        }

        $this->command->info('Restaurants & Invoice settings imported successfully from JSON file.');
    }
}
