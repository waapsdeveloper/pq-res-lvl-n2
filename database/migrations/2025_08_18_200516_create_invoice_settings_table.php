<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('restaurant_id')->unique();
            $table->string('invoice_logo')->nullable();
            $table->string('size')->default('80mm');
            $table->string('left_margin')->default('1mm');
            $table->string('right_margin')->default('1mm');
            $table->string('google_review_barcode')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('restaurant_address')->nullable();
            $table->integer('font_size')->default(10);
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};
