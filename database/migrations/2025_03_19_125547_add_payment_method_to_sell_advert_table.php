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
        Schema::table('sell_adverts', function (Blueprint $table) {
            $table->string('payment_method')->default('Bank transfer')->after('terms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_advert', function (Blueprint $table) {
            $table->dropColumn(['payment_method']);
        });
    }
};
