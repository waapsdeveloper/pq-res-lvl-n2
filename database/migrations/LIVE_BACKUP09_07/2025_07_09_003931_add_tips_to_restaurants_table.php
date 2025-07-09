<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurants', 'tips')) {
                $table->decimal('tips', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('restaurants', 'delivery_charges')) {
                $table->decimal('delivery_charges', 8, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            if (Schema::hasColumn('restaurants', 'tips')) {
                $table->dropColumn('tips');
            }
            if (Schema::hasColumn('restaurants', 'delivery_charges')) {
                $table->dropColumn('delivery_charges');
            }
        });
    }
};
