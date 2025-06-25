<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Actions\ProcessCompletedGiftCardOrder;
use Carbon\Carbon;

class CompleteDeliveredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:complete-delivered';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process completed gift card orders by crediting users with resale value';

    /**
     * Execute the console command.
     */
    public function handle(ProcessCompletedGiftCardOrder $processOrder)
    {
        $now = Carbon::now();
        $orders = Order::query()
            ->where('status', OrderStatus::Paid)
            ->where('delivery_time', '<=', $now)
            ->with(['user', 'giftCard'])
            ->get();

        $count = 0;
        $errors = [];

        foreach ($orders as $order) {
            try {
                $processOrder->execute($order);
                $count++;
                $this->info("Processed order #{$order->reference} - Credited \${$order->resale_amount} to {$order->user->username}");
            } catch (\Exception $e) {
                $errors[] = "Order #{$order->reference}: " . $e->getMessage();
                $this->error("Failed to process order #{$order->reference}: " . $e->getMessage());
            }
        }

        $this->info("Successfully processed {$count} order(s) with resale value.");

        if (!empty($errors)) {
            $this->warn("Encountered " . count($errors) . " error(s) during processing.");
        }

        return 0;
    }
}