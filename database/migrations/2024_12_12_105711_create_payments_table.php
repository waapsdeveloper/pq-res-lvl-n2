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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2);
            $table->string('customer_id');
            $table->enum('payment_status', ['pending', 'received', 'canceled'])->default('pending');
            $table->enum('payment_mode', ['none', 'cash', 'card', 'transfer'])->default('none');
            $table->enum('payment_portal', ['none', 'cash', 'stripe', 'paypal'])->default('none');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
