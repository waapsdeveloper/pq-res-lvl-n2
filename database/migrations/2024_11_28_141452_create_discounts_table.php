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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key 'id'
            $table->string('title'); // Discount title
            $table->text('description'); // Discount description
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key referencing products table
            $table->decimal('actual_price', 10, 2); // Actual price before discount
            $table->decimal('discount_price', 10, 2); // Discounted price
            $table->date('start_date'); // Start date of the discount
            $table->date('end_date'); // End date of the discount
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
