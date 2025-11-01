<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL ENUM update — must use raw SQL
        DB::statement("ALTER TABLE `orders` MODIFY `source` ENUM('pos', 'website', 'kiosk') DEFAULT 'pos'");
    }

    public function down(): void
    {
        // Revert back to original ENUM (if needed)
        DB::statement("ALTER TABLE `orders` MODIFY `source` ENUM('pos', 'website') DEFAULT 'pos");
    }
};
