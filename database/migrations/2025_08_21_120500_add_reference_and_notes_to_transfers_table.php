<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            if (! Schema::hasColumn('transfers', 'reference')) {
                $table->string('reference')->nullable()->after('id');
            }
            if (! Schema::hasColumn('transfers', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        // Backfill references for existing rows
        if (Schema::hasTable('transfers')) {
            \App\Models\Transfer::query()->whereNull('reference')->each(function ($t) {
                $t->reference = 'TRF-' . strtoupper(uniqid());
                $t->saveQuietly();
            });
        }

        Schema::table('transfers', function (Blueprint $table) {
            if (Schema::hasColumn('transfers', 'reference')) {
                try {
                    $table->string('reference')->unique()->change();
                } catch (Throwable $e) {
                    // Some drivers (like SQLite) don't support changing/adding unique easily. Ignore.
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            if (Schema::hasColumn('transfers', 'reference')) {
                try {
                    $table->dropUnique('transfers_reference_unique');
                } catch (Throwable $e) {
                    // ignore
                }
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('transfers', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
