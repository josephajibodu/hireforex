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
        Schema::create('gift_card_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_card_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_card_units');
    }
};