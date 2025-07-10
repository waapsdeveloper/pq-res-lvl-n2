<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class SuperAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/superadmin.json');

        // Read and decode JSON
        $users = json_decode(File::get($jsonFilePath), true);

        // Insert users into the databaseor
        foreach ($users as $user) {
            \App\Models\User::firstOrCreate(
                ['email' => $user['email']], // Unique field(s) to check
                [
                    'name' => $user['name'],
                    'email_verified_at' => $user['email_verified_at'] ?? null,
                    'phone' => $user['phone'] ?? "+" . 921324124,
                    'password' => Hash::make($user['password']),
                    'role_id' => $user['role_id'],
                    'restaurant_id' => $user['restaurant_id'] ?? 0,
                    'status' => $user['status'],
                    'image' => $user['image'],
                    'dial_code' => $user['dial_code'],
                    'remember_token' => str()->random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Users imported successfully from JSON file.');
    }
}
