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
        Schema::table('withdrawals', function (Blueprint $table) {
            // Update the fee column comment to reflect 3% instead of 10%
            // Note: The actual fee calculation logic will be handled in the application code
            $table->decimal('fee', 10, 2)->comment('3% transaction fee')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Restore the original comment
            $table->decimal('fee', 10, 2)->comment('10% transaction fee')->change();
        });
    }
};
