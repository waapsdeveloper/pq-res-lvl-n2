<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;



class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/categories.json');

        // Read and decode JSON
        $categories = json_decode(File::get($jsonFilePath), true);

        // Insert categories into the database
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'category_id' => $category['category_id'],
                'restaurant_id' => $category['restaurant_id'] ?? 0,
                'identifier' => $category['identifier'] ?? "CAT-" . uniqid(),
                'description' => $category['description'],
                'image' => $category['image'],
                'status' => $category['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
