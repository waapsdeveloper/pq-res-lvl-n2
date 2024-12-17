<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RTableReservingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/rTable_reservings.json');

        // Read and decode JSON
        $rtables = json_decode(File::get($jsonFilePath), true);

        // Insert rtables into the database
        foreach ($rtables as $rtable) {
            DB::table('rtable_bookings')->insert([
                'rtable_id' => $rtable['rtable_id'],
                'description' => $rtable['description'],
                'status' => $rtable['status'],
                'booking_start' => $rtable['booking_start'],
                'booking_end' => $rtable['booking_end'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Rtables imported successfully from JSON file.');
    }
}
