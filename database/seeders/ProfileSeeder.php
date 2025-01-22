<?php

namespace Database\Seeders;

use App\Helpers\Identifier;
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
            $profile = DB::table('profiles')->insertGetId([
                'identifier' => "PROF-",
                'email' => $profile['email'],
                'phone' => $profile['phone'],
                'user_id' => $profile['user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $identifier = Identifier::make('profiles',  $profile, 4);

            // Update the category with the generated identifier
            DB::table('profiles')->where('id',  $profile)->update([
                'identifier' => $identifier,
            ]);
        }

        $this->command->info('Profiles imported successfully from JSON file.');
    }
}
