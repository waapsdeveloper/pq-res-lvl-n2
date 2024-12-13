<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;



class OrderProductSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/order_products.json');

        // Read and decode JSON
        $orderProducts = json_decode(File::get($jsonFilePath), true);

        // Insert order products into the database
        foreach ($orderProducts as $product) {
            DB::table('order_products')->insert([
                'order_id' => $product['order_id'],
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'notes' => $product['notes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Order products imported successfully from JSON file.');
    }
}
