<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class RTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 30; $i++) {
            $data[] = [
                'restaurant_id' => rand(1, 5), // Assuming there are 5 restaurants
                'identifier' => 'TBL' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'location' => $this->randomLocation(),
                'description' => $this->randomDescription(),
                'status' => $this->randomStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data into the rtable table
        \DB::table('rtables')->insert($data);
    }

    /**
     * Generate a random location.
     *
     * @return string
     */
    private function randomLocation()
    {
        $locations = [
            'Ground Floor - Near Entrance',
            'First Floor - Corner',
            'Rooftop - Near Bar',
            'Patio Area',
            'VIP Section'
        ];
        return $locations[array_rand($locations)];
    }

    /**
     * Generate a random description.
     *
     * @return string
     */
    private function randomDescription()
    {
        $descriptions = [
            'Perfect for couples with a scenic view.',
            'A quiet table for business meetings.',
            'Convenient for walk-in customers.',
            'Great for families with kids.',
            'Close to the kitchen for quick service.'
        ];
        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Generate a random status.
     *
     * @return string
     */
    private function randomStatus()
    {
        $statuses = ['active', 'reserved', 'maintenance'];
        return $statuses[array_rand($statuses)];
    }
}
