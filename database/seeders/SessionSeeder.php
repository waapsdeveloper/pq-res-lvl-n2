<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SessionSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/sessions.json');

        // Read and decode JSON
        $sessions = json_decode(File::get($jsonFilePath), true);

        // Insert sessions into the database
        foreach ($sessions as $session) {
            DB::table('sessions')->insert([
                'id' => $session['id'],
                'user_id' => $session['user_id'],
                'ip_address' => $session['ip_address'],
                'user_agent' => $session['user_agent'],
                'payload' => $session['payload'],
                'last_activity' => $session['last_activity'],
            ]);
        }

        $this->command->info('Sessions imported successfully from JSON file.');
    }
}
