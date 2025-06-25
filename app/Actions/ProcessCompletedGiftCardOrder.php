<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ProcessCompletedGiftCardOrder
{
    /**
     * Process a completed gift card order by crediting the user with resale value.
     *
     * @param Order $order
     * @return void
     * @throws Exception
     */
    public function execute(Order $order): void
    {
        if ($order->status !== OrderStatus::Paid) {
            throw new Exception('Order is not in paid status.');
        }

        if (now()->lessThan($order->delivery_time)) {
            throw new Exception('Delivery time has not elapsed yet.');
        }

        DB::transaction(function () use ($order) {
            // Calculate resale value (quantity * resell_value per gift card)
            $resaleValue = $order->quantity * $order->giftCard->resell_value;

            // Credit the user's main balance with the resale value
            $order->user->credit($resaleValue, "Gift card resale - {$order->giftCard->name} x{$order->quantity} (Resale value: \${$resaleValue})");

            // Update order status to completed
            $order->update([
                'status' => OrderStatus::Completed,
                'resale_amount' => $resaleValue,
                'completed_at' => now()
            ]);
        });
    }
}