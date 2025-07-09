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
        Schema::table('branch_configs', function (Blueprint $table) {
            if (!Schema::hasColumn('branch_configs', 'country')) {
                $table->string('country')->default('United States')->after('branch_id');
            }
            if (!Schema::hasColumn('branch_configs', 'currency_symbol')) {
                $table->string('currency_symbol')->nullable()->after('currency');
            }

            if (!Schema::hasColumn('branch_configs', 'delivery_charges')) {
                $table->decimal('delivery_charges', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('branch_configs', 'enableDeliveryCharges')) {
                $table->boolean('enableDeliveryCharges')->default(true);
            }
            if (!Schema::hasColumn('branch_configs', 'enableTax')) {
                $table->boolean('enableTax')->default(true);
            }
            if (!Schema::hasColumn('branch_configs', 'tips')) {
                $table->decimal('tips', 8, 2)->default(0);
            }
            // created_at and updated_at are already handled by $table->timestamps()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_configs', function (Blueprint $table) {
            if (Schema::hasColumn('branch_configs', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('branch_configs', 'currency_symbol')) {
                $table->dropColumn('currency_symbol');
            }
            if (Schema::hasColumn('branch_configs', 'tips')) {
                $table->dropColumn('tips');
            }
            if (Schema::hasColumn('branch_configs', 'delivery_charges')) {
                $table->dropColumn('delivery_charges');
            }
            if (Schema::hasColumn('branch_configs', 'enableDeliveryCharges')) {
                $table->dropColumn('enableDeliveryCharges');
            }
            if (Schema::hasColumn('branch_configs', 'enableTax')) {
                $table->dropColumn('enableTax');
            }
        });
    }
};
