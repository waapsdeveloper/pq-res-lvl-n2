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
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('favicon')->nullable();
            $table->text('logo')->nullable();
            $table->text('copyright_text')->nullable();
            $table->decimal('rating', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('is_active')->default('0');
            $table->string('dial_code')->nullable();
            $table->string('tax')->nullable();
            $table->string('currency')->default('USD');

            $table->timestamps();
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
