<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsPropsSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/product_props.json');

        // Read and decode JSON
        $productProps = json_decode(File::get($jsonFilePath), true);

        // Insert product props into the database
        foreach ($productProps as $prop) {
            DB::table('product_props')->insert([
                'product_id' => $prop['product_id'],
                'meta_key' => $prop['meta_key'],
                'meta_value' => $prop['meta_value'],
                'meta_key_type' => $prop['meta_key_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Product properties imported successfully from JSON file.');
    }
}
