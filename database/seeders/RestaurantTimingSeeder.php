<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RestaurantTimingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = database_path('data/restaurant_timings.json');

        // Read and decode JSON
        $rtables = json_decode(File::get($jsonFilePath), true);

        // Insert rtables into the database
        foreach ($rtables as $rtable) {
            DB::table('restaurant_timings')->insert([
                'restaurant' => $rtable['restaurant'],
                'day' => $rtable['day'],
                'start_time' => $rtable['start_time'],
                'end_time' => $rtable['end_time'],
                'created_at' => now(),
                'updated_at' => now(),

            ]);
        }
    }
}
