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
        Schema::table('users', function (Blueprint $table) {
            // Adding foreign keys and new fields
            $table->unsignedBigInteger('role_id')->nullable()->after('id'); // Foreign key to roles table
            $table->unsignedBigInteger('restaurant_id')->nullable()->after('role_id'); // Foreign key to restaurants table
            $table->enum('status', ['active', 'inactive'])->default('active')->after('restaurant_id'); // User status

            // Setting up foreign key constraints (optional)
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the added columns and foreign key constraints
            $table->dropForeign(['role_id']);
            $table->dropForeign(['restaurant_id']);
            $table->dropColumn(['role_id', 'restaurant_id', 'status']);
        });
    }
};
