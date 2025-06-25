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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2);
            $table->decimal('amount_payable', 15, 2);

            $table->string('withdrawal_method')->comment('usdt_address, bybit_uid');
            $table->string('usdt_address')->nullable()->comment('USDT wallet address');
            $table->string('network_type')->nullable()->comment('BEP-20, TRC-20');
            $table->string('bybit_uid')->nullable()->comment('Bybit UID');

            $table->string('reference')->unique(); // Unique reference number
            $table->string('status')->default('pending')->comment('pending, completed, cancelled, rejected');

            $table->text('admin_notes')->nullable()->comment('Admin notes for processing');
            $table->timestamp('processed_at')->nullable()->comment('When withdrawal was processed');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};