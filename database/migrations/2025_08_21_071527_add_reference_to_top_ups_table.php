<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('top_ups', 'reference')) {
            Schema::table('top_ups', function (Blueprint $table) {
                $table->string('reference')->nullable()->after('id');
            });
        }

        // Backfill existing rows with unique references where missing
        DB::table('top_ups')->whereNull('reference')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $ref = 'TOP-' . strtoupper(bin2hex(random_bytes(4)));
                DB::table('top_ups')->where('id', $row->id)->update(['reference' => $ref]);
            }
        });

        // Add unique index if not already present
        try {
            Schema::table('top_ups', function (Blueprint $table) {
                $table->unique('reference');
            });
        } catch (\Throwable $e) {
            // Ignore if the unique index already exists (SQLite re-runs, etc.)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('top_ups', 'reference')) {
            Schema::table('top_ups', function (Blueprint $table) {
                $table->dropUnique('top_ups_reference_unique');
                $table->dropColumn('reference');
            });
        }
    }
};
