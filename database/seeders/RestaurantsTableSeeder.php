<?php

namespace Database\Seeders;

use App\Helpers\Helper;
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
                'description' => $restaurant['description'],
                'rating' => $restaurant['rating'],
                // 'image' => Helper::getBase64ImageUrl($restaurant['image'], 'restaurant'),
                // 'favicon' => Helper::getBase64ImageUrl($restaurant['favicon'], 'restaurant'),
                // 'logo' => Helper::getBase64ImageUrl($restaurant['logo'], 'restaurant'),
                'image' => $restaurant['image'],
                'favicon' => $restaurant['favicon'],
                'logo' => $restaurant['logo'],
                'status' => $restaurant['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $this->command->info('Restaurants imported successfully from JSON file.');
    }
}
