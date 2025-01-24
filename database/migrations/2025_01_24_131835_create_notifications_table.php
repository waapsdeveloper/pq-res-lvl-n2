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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Unique identifier for the notification
            $table->string('type'); // Notification class name
            $table->json('notifiable'); // Polymorphic relation (type and ID)
            $table->json('data'); // Notification data in JSON format
            $table->timestamp('read_at')->nullable(); // Read timestamp
            $table->timestamps(); // Created 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
