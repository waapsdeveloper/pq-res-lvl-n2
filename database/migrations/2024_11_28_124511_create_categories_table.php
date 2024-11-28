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
        Schema::create('categories', function (Blueprint $table) {
        $table->id(); // Creates an auto-incrementing unsigned big integer as 'id'
        $table->string('name'); // Category name
        $table->string('slug')->unique(); // Unique slug
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade'); // Parent category ID
        $table->timestamps(); // created_at and updated_at columns
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
