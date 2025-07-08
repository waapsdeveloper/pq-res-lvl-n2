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
            // Add missing fields
            if (!Schema::hasColumn('restaurants', 'country')) {
                $table->string('country')->default('United States');
            }
            if (!Schema::hasColumn('restaurants', 'enableTax')) {
                $table->boolean('enableTax')->default(true)->after('country');
            }
            if (!Schema::hasColumn('restaurants', 'enableDeliveryCharges')) {
                $table->boolean('enableDeliveryCharges')->default(true)->after('enableTax');
            }

            // Change data types for existing fields if they exist
            if (!Schema::hasColumn('restaurants', 'tax')) {
                $table->decimal('tax', 8, 2)->default(0);
            }
            if (Schema::hasColumn('restaurants', 'tips')) {
                $table->decimal('tips', 8, 2)->default(0)->change();
            }
            if (Schema::hasColumn('restaurants', 'delivery_charges')) {
                $table->decimal('delivery_charges', 8, 2)->default(0)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn(['country', 'enableTax', 'enableDeliveryCharges']);

            // Revert data types
            $table->string('tax')->nullable()->change();
            $table->string('tips')->nullable()->change();
            $table->string('delivery_charges')->nullable()->change();
        });
    }
};
