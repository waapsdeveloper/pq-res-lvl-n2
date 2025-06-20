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
        Schema::create('branch_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unique(); // Foreign key to the branch/restaurant table
            $table->decimal('tax', 5, 2)->default(0); // Tax percentage
            $table->string('currency')->nullable(); // Currency code (e.g., USD, EUR)
            $table->string('dial_code', 10)->nullable(); // Dial code for phone numbers
            $table->string('currency_symbol', 10)->nullable(); // Currency symbol (e.g., $, €, £)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('branch_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_configs');
    }
};
