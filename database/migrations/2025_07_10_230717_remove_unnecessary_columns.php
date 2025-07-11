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
            // Remove the specified columns from restaurants table
            $table->dropColumn([
                'home_page_title',
                'website',
                'copyright_text',
                'rating',
                'dial_code',
                'tax',
                'tips',
                'delivery_charges',
                'currency',
                'currency_symbol',
                'country',
                'enableTax',
                'enableDeliveryCharges'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Add back the columns in case of rollback
            $table->string('home_page_title')->nullable();
            $table->string('website')->nullable();
            $table->text('copyright_text')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->string('dial_code')->nullable();
            $table->decimal('tax', 5, 2)->default(0.00);
            $table->decimal('tips', 5, 2)->default(0.00);
            $table->decimal('delivery_charges', 8, 2)->default(0.00);
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('country')->nullable();
            $table->boolean('enableTax')->default(false);
            $table->boolean('enableDeliveryCharges')->default(false);
        });
    }
};
