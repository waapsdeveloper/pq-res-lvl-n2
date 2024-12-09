<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rtables', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID field
            $table->unsignedBigInteger('restaurant_id'); // Foreign key to restaurants (assuming you have a restaurants table)
            $table->string('identifier')->unique(); // A unique identifier for the table
            $table->string('location'); // Location of the table
            $table->text('description')->nullable(); // A description of the table
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtables');
    }
};
