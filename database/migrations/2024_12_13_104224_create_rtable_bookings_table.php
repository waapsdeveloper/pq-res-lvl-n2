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
            $table->biginteger('customer_id')->nullable();
            $table->biginteger('restaurant_id')->nullable();            
            $table->bigInteger('order_id')->nullable();
            $table->dateTime('booking_start')->nullable();
            $table->dateTime('booking_end')->nullable();
            $table->bigInteger('no_of_seats')->default(0)->nullable();
            $table->string('description')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'checked_in', 'checked_out', 'no_show', 'reserved', 'completed'])->default('pending');
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
