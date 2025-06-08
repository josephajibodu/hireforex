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
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->decimal('resell_value', 10, 2);
            $table->integer('delivery_duration')->comment('in hours');
            $table->integer('available_units')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};