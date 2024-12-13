<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/payments.json');

        // Read and decode JSON
        $payments = json_decode(File::get($jsonFilePath), true);

        // Insert payments into the database
        foreach ($payments as $payment) {
            DB::table('payments')->insert([
                'order_id' => $payment['order_id'],
                'amount' => $payment['amount'],
                'customer_id' => $payment['customer_id'],
                'status' => $payment['status'],
                'mode' => $payment['mode'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Payments imported successfully from JSON file.');
    }
}
