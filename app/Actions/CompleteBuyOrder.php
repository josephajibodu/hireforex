<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Enums\WalletType;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;

class CompleteBuyOrder
{
    public function __construct()
    {}

    /**
     * Complete a buy order.
     *
     * @param Order $order
     *
     * @return void
     * @throws Exception
     */
    public function execute(Order $order): void
    {
        if (! $order->inPendingState()) {
            throw new Exception("Only pending orders can be marked as successful.");
        }

        DB::transaction(function () use ($order) {
            $sellAdvert = $order->sellAdvert;

            if (!$sellAdvert) {
                throw new Exception("Sell advert associated with this order does not exist.");
            }

            $coinAmount = $order->coin_amount;

            if ($sellAdvert->remaining_balance < $coinAmount) {
                throw new Exception("Insufficient remaining balance in the sell advert.");
            }

            $sellAdvert->user->debit(WalletType::Trading, $coinAmount / 100, "Sell order completion for order #$order->reference");

            // Update sell advert's remaining balance
            $sellAdvert->decrement('remaining_balance', $coinAmount);

            // Credit the buyer's reserve wallet
            $buyer = $order->buyer;
            $buyer->credit(WalletType::Reserve, $coinAmount / 100, "Buy order completion for order #$order->reference", WalletType::Reserve);

            // Update the order status to successful
            $order->update(['status' => OrderStatus::Completed]);
        });
    }
}