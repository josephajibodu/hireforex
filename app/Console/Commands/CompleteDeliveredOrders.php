<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Enums\OrderStatus;
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $orders = Order::query()
            ->where('status', OrderStatus::Paid)
            ->where('delivery_time', '<=', $now)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $order->status = OrderStatus::Completed;
            $order->save();
            $count++;
        }

        $this->info("Marked {$count} order(s) as completed.");
        return 0;
    }
}