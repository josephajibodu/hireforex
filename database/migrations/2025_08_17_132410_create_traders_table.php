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
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('experience_years');
            $table->string('favorite_pairs');
            $table->string('track_record', 5); // WLWLW format
            $table->decimal('mbg_rate', 5, 2); // Money Back Guarantee rate (80.00 to 100.00)
            $table->decimal('min_capital', 10, 2); // Minimum acceptable capital
            $table->decimal('available_volume', 12, 2); // Available volume for trading
            $table->integer('duration_days'); // Trade duration in days
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['is_available', 'mbg_rate']);
            $table->index(['min_capital', 'available_volume']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traders');
    }
};
