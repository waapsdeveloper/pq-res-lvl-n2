<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/variations.json');

        // Read and decode JSON
        $variations = json_decode(File::get($jsonFilePath), true);

        // Insert variations into the database
        foreach ($variations as $variation) {
            DB::table('variations')->insert([
                'name' => $variation['name'],
                'description' => $variation['description'],
                'meta_value' => json_encode($variation['meta_value']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
