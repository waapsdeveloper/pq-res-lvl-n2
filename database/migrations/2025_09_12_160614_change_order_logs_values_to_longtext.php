<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrderLogsValuesToLongtext extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_logs', function (Blueprint $table) {
            $table->longText('old_value')->nullable()->change();
            $table->longText('new_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_logs', function (Blueprint $table) {
            $table->string('old_value', 255)->nullable()->change();
            $table->string('new_value', 255)->nullable()->change();
        });
    }
};
