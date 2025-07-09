<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $migrationsPath = database_path('migrations');
        $files = scandir($migrationsPath);
        $batch = 1; // You can set this to the latest batch number if needed
        $now = now();

        foreach ($files as $file) {
            if (Str::endsWith($file, '.php')) {
                $migrationName = str_replace('.php', '', $file);
                // Check if already exists
                $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
                if (!$exists) {
                    DB::table('migrations')->insert([
                        'migration' => $migrationName,
                        'batch' => $batch,
                    ]);
                }
            }
        }
    }
} 