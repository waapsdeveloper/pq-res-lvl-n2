<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/orders.json');

        // Read and decode JSON
        $orders = json_decode(File::get($jsonFilePath), true);

        // Insert orders into the database
        foreach ($orders as $order) {
            $orderId = DB::table('orders')->insertGetId([
                'identifier' => $order['identifier'] ?? "OD-" . uniqid(),
                'order_number' => $order['order_number'],
                'type' => $order['type'],
                'status' => $order['status'],
                'notes' => $order['notes'],
                'customer_id' => $order['customer_id'],
                'invoice_no' => $order['invoice_no'],
                'table_no' => $order['table_no'],
                'restaurant_id' => $order['restaurant_id'],
                'total_price' => $order['total_price'],
                'discount' => $order['discount'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($order['products'] as $product) {
                DB::table('order_products')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Orders imported successfully from JSON file.');
    }
}
