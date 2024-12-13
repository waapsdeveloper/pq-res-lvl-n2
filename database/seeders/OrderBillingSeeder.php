<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
class OrderBillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/order_billing.json');

        // Read and decode JSON
        $orderBillings = json_decode(File::get($jsonFilePath), true);

        // Insert order billing records into the database
        foreach ($orderBillings as $billing) {
            DB::table('order_billing')->insert([
                'order_id' => $billing['order_id'],
                'amount' => $billing['amount'],
                'tax' => $billing['tax'],
                'total' => $billing['total'],
                'status' => $billing['status'],
                'discount' => $billing['discount'],
                'total_price' => $billing['total_price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Order billing data imported successfully from JSON file.');
    }
}
