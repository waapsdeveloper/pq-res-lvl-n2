<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/order_products.json');

        // Check if the file exists
        if (!File::exists($jsonFilePath)) {
            $this->command->error("JSON file not found: $jsonFilePath");
            return;
        }

        // Read and decode JSON
        $orderProducts = json_decode(File::get($jsonFilePath), true);

        // Validate JSON structure
        if (!is_array($orderProducts)) {
            $this->command->error("Invalid JSON format in $jsonFilePath");
            return;
        }

        // Insert order products into the database
        foreach ($orderProducts as $orderProduct) {
            DB::table('order_products')->insert([
                'order_id' => $orderProduct['order_id'],
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Order products imported successfully from JSON file.');
    }
}
