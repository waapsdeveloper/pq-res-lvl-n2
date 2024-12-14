<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/users.json');

        // Read and decode JSON
        $users = json_decode(File::get($jsonFilePath), true);

        // Insert users into the database
        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'] ?? null, // Nullable
                'password' => bcrypt($user['password']), // Hashing password
                'role_id' => $user['role_id'],
                'restaurant_id' => $user['restaurant_id'] ?? 0,
                'status' => $user['status'],
                'remember_token' => str()->random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Users imported successfully from JSON file.');
    }

}
