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
            if (!Schema::hasColumn('restaurants', 'home_page_title')) {
                $table->string('home_page_title', 60)->nullable()->after('name');
            }

            // Change data types for existing fields if they exist
            if (!Schema::hasColumn('restaurants', 'tax')) {
                $table->decimal('tax', 8, 2)->default(0);
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
            // Remove added fields if they exist
            foreach (['country', 'enableTax', 'enableDeliveryCharges'] as $col) {
                if (Schema::hasColumn('restaurants', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('restaurants', 'home_page_title')) {
                $table->dropColumn('home_page_title');
            }

            // Revert data types for changed fields if they exist
            foreach (['tax', 'tips', 'delivery_charges'] as $col) {
                if (Schema::hasColumn('restaurants', $col)) {
                    $table->string($col)->nullable()->change();
                }
            }
        });
    }
};
