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
        Schema::create('rtable_booking_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rtable_booking_id');
            $table->unsignedBigInteger('payment_method');
            $table->unsignedBigInteger('payment_gateway');
            $table->string('payment_col1'); //need to change col names 
            $table->string('payment_col2'); //need to change col names
            $table->string('payment_status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtable_booking_payments');
    }
};
