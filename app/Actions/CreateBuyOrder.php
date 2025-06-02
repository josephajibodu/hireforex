<?php

namespace App\Actions;
namespace App\Actions;

use App\Enums\OrderStatus;
use App\Enums\SellAdvertType;
use App\Enums\TradeStatus;
use App\Enums\SellAdvertStatus;
use App\Models\Order;
use App\Models\PriceSchedule;
use App\Models\SellAdvert;
use App\Models\User;
use App\Settings\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateBuyOrder
{
    public function __construct(public GeneralSetting $generalSetting)
    {}

    /**
     * Create a new buy order.
     *
     * @param User $user
     * @param int $sellAdvertId
     * @param float $amount
     * @return mixed
     * @throws Exception
     */
    public function execute(User $user, int $sellAdvertId, float $amount): Order
    {
        if (! $user->kyc?->isCompleted()) {
            throw new Exception('Your identity must be verified before you can create a buy order.');
        }

        // check if the user already has a buy order
        $buyOrder = $user->buyOrders()->scopes('orderStillOn')->latest()->first();

        if ($buyOrder) {
            throw new Exception("Please complete your pending order before proceeding with another order.");
        }

        /** @var SellAdvert $sellAdvert */
        $sellAdvert = SellAdvert::query()->findOrFail($sellAdvertId);

        if ($sellAdvert->status == SellAdvertStatus::SoldOut) {
            throw new Exception('The dealer is already sold out. Please choose another dealer or try again later.');
        }

        if ($user->id === $sellAdvert->user->id) {
            throw new Exception("Invalid buy order");
        }

        $currentRate = getCurrentRate();

        $amountToPay = $amount * $currentRate;

        // Check if the amount is within the allowed range
        if ($amountToPay < $sellAdvert->minimum_sell) {
            throw new Exception("Amount is less than the minimum amount allowed for this sell order.");
        }

        if ($amountToPay > $sellAdvert->max_sell) {
            throw new Exception("Amount exceeds the maximum amount allowed for this sell order.");
        }

        if (($amount * 100) > $sellAdvert->available_balance) {
            throw new Exception("Amount exceeds the available USD for this sell order.");
        }

        return DB::transaction(function () use ($currentRate, $amountToPay, $user, $sellAdvert, $amount) {
            $isUsdtPayment = $sellAdvert->type === SellAdvertType::Usdt;

            // Create the order
            $order = Order::query()->create([
                'reference' => Str::uuid(),
                'user_id' => $user->id,
                'sell_advert_id' => $sellAdvert->id,
                'coin_amount' => $coinAmt = $amount * 100,
                'total_amount' => $amountToPay,
                'seller_unit_price' => $currentRate,
                'payment_time_limit' => $this->generalSetting->order_time_limit,
                'status' => OrderStatus::Pending,
                'type' => $sellAdvert->type,
                'wallet_address' => $isUsdtPayment ? $sellAdvert->wallet_address : null,
                'network_type' => $isUsdtPayment ? $sellAdvert->network_type : null,
            ]);

            // Reduce the available balance of the sell advert
            $sellAdvert->decrement('available_balance', $coinAmt);

            if ($sellAdvert->refresh()->available_balance < 0) {
                throw new Exception("Amount exceeds the available USD for this sell order.");
            }

            return $order;
        });
    }
}