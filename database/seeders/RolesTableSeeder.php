<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the roles JSON file
        $jsonFilePath = database_path('data/roles.json');

        // Check if the file exists
        if (!File::exists($jsonFilePath)) {
            $this->command->error("File not found: $jsonFilePath");
            return;
        }

        // Read the JSON file
        $jsonData = File::get($jsonFilePath);

        // Decode the JSON data into an array
        $roles = json_decode($jsonData, true);

        // Insert each role into the database
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'id' => $role['id'], // Explicitly insert IDs if needed
                'name' => $role['name'],
                'slug' => $role['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Roles imported successfully from JSON file.');
    }
}
