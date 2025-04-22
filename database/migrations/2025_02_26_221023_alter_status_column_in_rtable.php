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
            // If needed, revert the change (you may need to define ENUM values manually)
            DB::statement("ALTER TABLE `rtables` MODIFY `status` ENUM('pending', 'approved', 'rejected') NOT NULL");
        });
    }
};
