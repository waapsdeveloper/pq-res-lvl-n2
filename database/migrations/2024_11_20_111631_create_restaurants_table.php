<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Name of the restaurant
            $table->string('address'); // Address of the restaurant
            $table->string('phone')->nullable(); // Phone number (optional)
            $table->string('email')->nullable(); // Email address (optional)
            $table->string('website')->nullable(); // Website URL (optional)
            $table->text('description')->nullable(); // Description of the restaurant (optional)
            $table->float('rating')->default(0); // Average rating, default to 0
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
};
