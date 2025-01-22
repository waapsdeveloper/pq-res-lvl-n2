<?php

namespace Database\Seeders;

use App\Helpers\Identifier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RTableSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/rtables.json');

        // Read and decode JSON
        $rtables = json_decode(File::get($jsonFilePath), true);

        // Insert rtables into the database
        foreach ($rtables as $rtable) {
            $rtable = DB::table('rtables')->insertGetId([
                'name' => $rtable['name'],
                'restaurant_id' => $rtable['restaurant_id'],
                'identifier' => "RTABLE-",
                'status' => $rtable['status'],
                'no_of_seats' => $rtable['no_of_seats'],
                'description' => $rtable['description'],
                'floor' => $rtable['floor'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $identifier = Identifier::make('rtables',  $rtable,4);

            // Update the category with the generated identifier
            DB::table('rtables')->where('id',  $rtable)->update([
                'identifier' => $identifier,
            ]);
        }

        $this->command->info('Rtables imported successfully from JSON file.');
    }
}
