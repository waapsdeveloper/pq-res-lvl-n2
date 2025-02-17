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
        Schema::table('rtable_bookings', function (Blueprint $table) {
            $table->string('order_number')->after('customer_id')->nullable(); // Replace 'column_name' with an existing column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rtable_bookings', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
