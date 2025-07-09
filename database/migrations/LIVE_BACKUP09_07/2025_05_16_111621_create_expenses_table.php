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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('expense_category_id');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['recurring', 'one-time']);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->date('date');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('expense_category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
