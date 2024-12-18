<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantsTableSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/restaurants.json');

        // Read and decode JSON
        $restaurants = json_decode(File::get($jsonFilePath), true);

        // Insert restaurants into the database
        foreach ($restaurants as $restaurant) {
            DB::table('restaurants')->insert([
                'name' => $restaurant['name'],
                'address' => $restaurant['address'],
                'phone' => $restaurant['phone'],
                'email' => $restaurant['email'],
                'website' => $restaurant['website'],
                // 'opening_hours' => json_encode($restaurant['opening_hours']), // JSON کو اسٹرنگ میں کنورٹ کریں
                'description' => $restaurant['description'],
                'rating' => $restaurant['rating'],
                'status' => $restaurant['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $this->command->info('Restaurants imported successfully from JSON file.');
    }
}
