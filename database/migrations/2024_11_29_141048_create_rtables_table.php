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
            $table->id();
            $table->unsignedBigInteger('restaurant');
            $table->string('identifier')->unique();
            $table->string('floor')->nullable();
            $table->integer('no_of_seats')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('rtables_reservings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('restaurant'); // Foreign key referencing the restaurant
            $table->unsignedBigInteger('rtable_id'); // Foreign key referencing the table
            $table->dateTime('booking_start'); // Date and time for the reservation
            $table->dateTime('booking_end');
            $table->string('floor')->nullable();
            // Date and time for the reservation
            $table->enum('status', ['available', 'unavailable'])->default('available'); // Status of the reservation
            $table->text('description')->nullable(); // Optional description for the reservation
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('rtable_id')->references('id')->on('rtables')->onDelete('cascade');
            // Date range validation

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtables_reservings');
        Schema::dropIfExists('rtables');
    }
};
