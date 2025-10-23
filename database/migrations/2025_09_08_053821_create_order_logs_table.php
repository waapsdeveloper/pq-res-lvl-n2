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
    Schema::create('order_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id'); // link to orders table
        $table->string('event_type');           // e.g. status_change, payment_status, note_added
        $table->longText('old_value')->nullable(); // what it was before
        $table->longText('new_value')->nullable(); // what it changed to
        $table->string('performed_by')->nullable(); // name or role (Admin, Customer, Rider)
        $table->unsignedBigInteger('performed_by_id')->nullable(); // user_id or admin_id
        $table->timestamps();

        // foreign key (if you want referential integrity)
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
