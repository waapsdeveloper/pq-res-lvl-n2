<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rtables', function (Blueprint $table) {
            // Change the column type from ENUM to STRING
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rtables', function (Blueprint $table) {
            // Get existing status values
            $existingStatuses = DB::table('rtables')->distinct()->pluck('status')->toArray();

            // Filter out null values and escape single quotes for use in SQL
            $enumValues = array_map(function ($value) {
                return "'" . str_replace("'", "''", $value) . "'";
            }, array_filter($existingStatuses, 'strlen'));

            // Implode the values to create the ENUM definition
            $enumDefinition = implode(',', $enumValues);

            // Modify the status column to be an ENUM with all existing values
            DB::statement("ALTER TABLE `rtables` MODIFY `status` ENUM(" . $enumDefinition . ") NOT NULL");
        });
    }
};
