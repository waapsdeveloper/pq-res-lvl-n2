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
        Schema::create('restaurant_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->string('meta_key');
            $table->text('meta_value')->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('restaurant_id')
                  ->references('id')
                  ->on('restaurants')
                  ->onDelete('cascade');

            // Add unique constraint to prevent duplicate meta keys for same restaurant
            $table->unique(['restaurant_id', 'meta_key']);

            // Add indexes for better performance
            $table->index(['restaurant_id']);
            $table->index(['meta_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_meta');
    }
}; 