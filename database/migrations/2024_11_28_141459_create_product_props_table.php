<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_props', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key 'id'
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key
            $table->string('meta_key')->nullable(); // Meta key (e.g., 'color', 'size')
            $table->string('meta_value')->nullable(); // Meta value corresponding to the meta key
            $table->string('meta_key_type')->nullable(); // Type of the meta key (e.g., 'string', 'integer', 'boolean')

            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_props');
    }
};
