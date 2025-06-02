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
        Schema::table('sell_adverts', function (Blueprint $table) {
            $table->string('type')->default(SellAdvertType::Local->value)->after('user_id');
            $table->string('network_type')->nullable()->after('bank_account_number');
            $table->string('wallet_address')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_adverts', function (Blueprint $table) {
            $table->dropColumn(['type', 'wallet_address', 'network_type']);
        });
    }
};
