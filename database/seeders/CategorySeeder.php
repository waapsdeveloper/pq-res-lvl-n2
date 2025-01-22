<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Helpers\Identifier;
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
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'category_id' => $category['category_id'],
                'restaurant_id' => $category['restaurant_id'] ?? 0,
                'identifier' => "CAT",
                'description' => $category['description'],
                'image' => $category['image'],
                'status' => $category['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $identifier = Identifier::make('categories', $categoryId);

            // Update the category with the generated identifier
            DB::table('categories')->where('id', $categoryId)->update([
                'identifier' => $identifier,
            ]);
        }
    }
}
