<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Helpers\Identifier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/products.json');

        // Read and decode JSON
        $products = json_decode(File::get($jsonFilePath), true);

        // Insert products into the database
        foreach ($products as $product) {
            $productid = DB::table('products')->insertGetId([
                'category_id' => $product['category_id'],
                'restaurant_id' => $product['restaurant_id'],
                'identifier' => "PROD-" . uniqid(),
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'discount' => $product['discount'],
                'image' => $product['image'],
                'status' => $product['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $identifier = Identifier::make('products',  $productid, 3);

            // Update the category with the generated identifier
            DB::table('products')->where('id',  $productid)->update([
                'identifier' => $identifier,
            ]);
        }

        $this->command->info('Products imported successfully from JSON file.');
    }
}
