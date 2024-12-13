<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/profiles.json');

        // Read and decode JSON
        $profiles = json_decode(File::get($jsonFilePath), true);

        // Insert profiles into the database
        foreach ($profiles as $profile) {
            DB::table('profiles')->insert([
                'identifier' => $profile['identifier'],
                'email' => $profile['email'],
                'phone' => $profile['phone'],
                'user_id' => $profile['user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Profiles imported successfully from JSON file.');
    }
}
