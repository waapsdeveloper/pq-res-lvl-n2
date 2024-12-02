<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/restaurants.json');

        // Check if the file exists
        if (!File::exists($jsonFilePath)) {
            $this->command->error("File not found: $jsonFilePath");
            return;
        }

        // Read the JSON file
        $jsonData = File::get($jsonFilePath);

        // Decode JSON data
        $restaurants = json_decode($jsonData, true);

        // Insert each restaurant into the database
        foreach ($restaurants as $restaurant) {
            DB::table('restaurants')->insert([
                'name' => $restaurant['name'],
                'address' => $restaurant['address'],
                'phone' => $restaurant['phone'] ?? null,
                'email' => $restaurant['email'] ?? null,
                'website' => $restaurant['website'] ?? null,
                'opening_hours' => json_encode($restaurant['opening_hours']),
                'description' => $restaurant['description'] ?? null,
                'rating' => $restaurant['rating'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Restaurants imported successfully from JSON file.');
    }
}
