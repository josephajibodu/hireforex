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
        // Drop the existing table and recreate it with new structure
        Schema::dropIfExists('top_ups');
        
        Schema::create('top_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('method'); // 'bybit' or 'usdt'
            $table->string('screenshot')->nullable();
            $table->string('bybit_email')->nullable();
            $table->string('network')->nullable(); // 'TRC-20' or 'BEP-20'
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['method', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new table and recreate the old one
        Schema::dropIfExists('top_ups');
        
        Schema::create('top_ups', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('bybit_email')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->string('status')->default('pending');
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }
};
