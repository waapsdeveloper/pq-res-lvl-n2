<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\RestaurantTiming;

class RestaurantTimingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = database_path('data/restaurant_timings.json');

        // Read and decode JSON
        $restaurantTimings = json_decode(File::get($jsonFilePath), true);

        // Insert restaurant timings into the database
        foreach ($restaurantTimings as $restaurantTiming) {
            $restaurantId = $restaurantTiming['restaurant_id'];
            $timings = $restaurantTiming['timings'];

            // Convert timings array to config array
            $config = [];
            foreach ($timings as $timing) {
                $key = $timing['key'];
                $value = $timing['value'];
                
                // Convert boolean strings to actual booleans
                if (in_array($value, ['true', 'false'])) {
                    $value = $value === 'true';
                }
                
                $config[$key] = $value;
            }

            // Use the model method to set timing configuration
            RestaurantTiming::setTimingConfig($restaurantId, $config);
        }
    }
}
