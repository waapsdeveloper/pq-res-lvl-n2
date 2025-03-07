<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $jsonFilePath = database_path('data/countries.json');

        // Read and decode JSON
        $countries = json_decode(File::get($jsonFilePath), true);
        // Insert countries into the database
        foreach ($countries as $country) {
            $countryId = DB::table('countries')->insertGetId([
                'name' => $country['name'],
                'dial_code' => $country['dial_code'],
                'code' => $country['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        }


    }
}
