<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/orders.json');

        // Check if the file exists
        if (!File::exists($jsonFilePath)) {
            $this->command->error("JSON file not found: $jsonFilePath");
            return;
        }

        // Read and decode JSON
        $orders = json_decode(File::get($jsonFilePath), true);

        // Validate JSON structure
        if (!is_array($orders)) {
            $this->command->error("Invalid JSON format in $jsonFilePath");
            return;
        }

        // Insert orders into the database
        foreach ($orders as $order) {
            DB::table('orders')->insert([
                'customer_name' => $order['customer_name'] ?? null,
                'customer_phone' => $order['customer_phone'] ?? null,
                'discount' => $order['discount'] ?? 0.00,
                'order_number' => $order['order_number'],
                'total_price' => $order['total_price'],
                'status' => $order['status'] ?? 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Orders imported successfully from JSON file.');
    }
}
