<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Enums\OrderStatus;
use Carbon\Carbon;

class UpdateCompletedOrdersResaleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-completed-resale-data {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing completed orders with resale_amount and completed_at values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        // Get all completed orders that don't have resale_amount set
        $orders = Order::query()
            ->where('status', OrderStatus::Completed)
            ->whereNull('resale_amount')
            ->with(['giftCard'])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No completed orders found that need resale data updates.');
            return 0;
        }

        $this->info("Found {$orders->count()} completed orders that need resale data updates.");

        $updatedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            try {
                // Calculate resale amount based on quantity and gift card resell value
                $resaleAmount = $order->quantity * $order->giftCard->resell_value;

                // Use delivery_time as completed_at if not set
                $completedAt = $order->completed_at ?? $order->delivery_time ?? now();

                if ($isDryRun) {
                    $this->line("Would update order #{$order->reference}:");
                    $this->line("  - Resale Amount: \${$resaleAmount}");
                    $this->line("  - Completed At: {$completedAt->format('Y-m-d H:i:s')}");
                    $this->line("  - Gift Card: {$order->giftCard->name} x{$order->quantity}");
                    $this->line("  - Original Amount: {$order->total_amount} USDT");
                    $this->line("");
                } else {
                    $order->update([
                        'resale_amount' => $resaleAmount,
                        'completed_at' => $completedAt
                    ]);

                    $this->info("Updated order #{$order->reference} - Resale: \${$resaleAmount}, Completed: {$completedAt->format('Y-m-d H:i:s')}");
                }

                $updatedCount++;

            } catch (\Exception $e) {
                $errorMsg = "Failed to update order #{$order->reference}: " . $e->getMessage();
                $errors[] = $errorMsg;
                $this->error($errorMsg);
            }
        }

        if ($isDryRun) {
            $this->info("DRY RUN COMPLETED - Would update {$updatedCount} orders");
        } else {
            $this->info("Successfully updated {$updatedCount} orders with resale data.");
        }

        if (!empty($errors)) {
            $this->warn("Encountered " . count($errors) . " error(s) during processing.");
        }

        return 0;
    }
}