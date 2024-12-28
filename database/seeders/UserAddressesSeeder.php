<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserAddressesSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/user_addresses.json');

        // Read and decode JSON
        $userAddresses = json_decode(File::get($jsonFilePath), true);

        // Insert user addresses into the database
        foreach ($userAddresses as $address) {
            DB::table('user_addresses')->insert([
                'user_id' => $address['user_id'],
                'address' => $address['address'],
                'city' => $address['city'],
                'state' => $address['state'],
                'country' => $address['country'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('User addresses imported successfully from JSON file.');
    }
}
