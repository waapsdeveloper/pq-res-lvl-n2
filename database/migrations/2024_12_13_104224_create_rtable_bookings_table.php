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
        Schema::create('rtable_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('rtable_id');
            $table->integer('customer_id');
            $table->integer('order_id');
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');
            $table->integer('number_of_people');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtable_bookings');
    }
};
