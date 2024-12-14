<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/roles.json');

        // Read and decode JSON
        $roles = json_decode(File::get($jsonFilePath), true);

        // Insert roles into the database
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'slug' => $role['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Roles imported successfully from JSON file.');
    }
}