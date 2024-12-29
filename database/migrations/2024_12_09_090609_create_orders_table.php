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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->nullable();
            $table->string('order_number')->nullable();
            $table->enum('type', ['dine-in', 'take-away', 'delivery', 'drive-thru', 'curbside-pickup', 'catering', 'reservation'])->default('dine-in'); // Enum column
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready_for_pickup', 'out_for_delivery', 'delivered', 'completed', 'cancelled'])->default('pending'); // Status enum
            $table->text('notes')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('table_no')->nullable();
            $table->string('restaurant_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
