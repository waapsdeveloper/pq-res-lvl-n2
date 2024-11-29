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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key
            $table->string('name'); // Product name
            $table->text('description'); // Product description
            $table->decimal('price', 10, 2); // Product price (10 digits total, 2 decimal places)
            $table->string('image')->nullable(); // Product image (nullable in case there's no image)
            $table->string('status')->nullable()->default('active'); // Product image (nullable in case there's no image)
            $table->timestamps(); // created_at and updated_at columns
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
