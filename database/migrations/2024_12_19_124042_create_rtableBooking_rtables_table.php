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
        Schema::create('rtableBooking_rtables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('rtable_booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('rtable_id')->constrained('rtables')->onDelete('cascade'); // Single foreign key for each rtable_id
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');
            $table->integer('no_of_seats')->default(0); // No default value needed if 'no_of_seats' is optional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_table_booking__r_tables');
    }
};
