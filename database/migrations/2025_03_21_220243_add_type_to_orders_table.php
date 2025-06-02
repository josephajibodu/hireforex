<?php

use App\Enums\SellAdvertType;
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
            // these are added so that we can track payment per order. such that
            // orders created with bank payment can remain bank payment, even when dealer
            // updates the sell advert to usdt payment while the buy order is still pending
            // vice versa, if usdt was enabled but switched back to local payment while an order
            // is still on. Also for cases where the dealer updates the wallet address, should only apply for new orders
            $table->string('type')->default(SellAdvertType::Local->value)->after('payment_proof');
            $table->string('network_type')->nullable()->after('payment_proof');
            $table->string('wallet_address')->nullable()->after('payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['type', 'wallet_address', 'network_type']);
        });
    }
};
