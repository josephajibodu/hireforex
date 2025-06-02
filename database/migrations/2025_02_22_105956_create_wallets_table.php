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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('main_balance')->default(0);
            $table->unsignedBigInteger('reserve_balance')->default(0);
            $table->unsignedBigInteger('trading_balance')->default(0);
            $table->unsignedBigInteger('bonus_balance')->default(0);
            $table->unsignedBigInteger('withdrawal_balance')->default(0);

            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('source')->comment('Where the funds are coming from: main, reserve, trading, external');
            $table->string('destination')->comment('Where the funds are going: main, reserve, trading, external');

            $table->string('type')->comment('credit, debit');
            $table->string('reason')->comment('deposit, purchase, transfer, withdrawal, etc.');
            $table->bigInteger('amount');
            $table->string('status')->default('pending')->comment('pending, committed');

            $table->timestamp('expires_at')->nullable()->comment('For reserved transactions that unlock after a period');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};
