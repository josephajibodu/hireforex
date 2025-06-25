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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('resale_amount', 10, 2)->nullable()->after('total_amount')->comment('Amount credited to user after delivery');
            $table->timestamp('completed_at')->nullable()->after('delivery_time')->comment('When the order was completed and resale value credited');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['resale_amount', 'completed_at']);
        });
    }
};