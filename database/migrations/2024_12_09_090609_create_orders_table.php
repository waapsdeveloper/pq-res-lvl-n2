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
            $table->enum('type', ['dine-in', 'take-away', 'delivery', 'drive-thru', 'curbside-pickup', 'catering', 'reservation'])->nullable();
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready_for_pickup', 'out_for_delivery', 'delivered', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->unsignedBigInteger('table_no')->nullable();
            $table->boolean('is_paid')->default(false); // ✅ keep this

            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable(); // Add this line
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('tips_amount', 5, 2)->nullable();
            $table->decimal('tips', 10, 2)->nullable();
            $table->decimal('delivery_charges', 10, 2)->nullable();
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
