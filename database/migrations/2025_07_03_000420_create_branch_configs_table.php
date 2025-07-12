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
        if (!Schema::hasTable('branch_configs')) {
            Schema::create('branch_configs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->unique();
                $table->string('country')->default('United States');
                $table->decimal('tax', 8, 2)->default(0);
                $table->string('currency')->default('USD');
                $table->string('dial_code')->default('+1');
                $table->string('currency_symbol')->default('$');
                $table->decimal('delivery_charges', 8, 2)->default(0);
                $table->decimal('tips', 8, 2)->default(0);
                $table->boolean('enableTax')->default(true);
                $table->boolean('enableDeliveryCharges')->default(true);
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('restaurants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_configs');
    }
};
