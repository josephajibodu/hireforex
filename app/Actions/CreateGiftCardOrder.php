<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Models\GiftCard;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateGiftCardOrder
{
    /**
     * Create a new gift card order.
     *
     * @param User $user
     * @param GiftCard $giftCard
     * @param int $quantity
     * @return Order
     * @throws Exception
     */
    public function execute(User $user, GiftCard $giftCard, int $quantity): Order
    {
        if (!$giftCard->is_available) {
            throw new Exception('This gift card is currently unavailable.');
        }

        $availableUnits = $giftCard->available->count();

        if ($quantity > $availableUnits) {
            throw new Exception("Only {$availableUnits} units are available for this gift card.");
        }

        $cost = $giftCard->amount * $quantity;
        $user->debit($cost, "Giftcard purchase - {$giftCard->name} x{$quantity}");

        return DB::transaction(function () use ($cost, $user, $giftCard, $quantity) {
            // Create the order
            $order = Order::create([
                'reference' => Str::uuid(),
                'user_id' => $user->id,
                'gift_card_id' => $giftCard->id,
                'quantity' => $quantity,
                'total_amount' => $cost,
                'delivery_time' => now()->addHours($giftCard->delivery_duration),
                'status' => OrderStatus::Paid,
            ]);

            // Get available units and mark them as used
            $units = $giftCard->available()->take($quantity)->get();

            // Update units with order_id and mark as used
            foreach ($units as $unit) {
                $unit->update([
                    'order_id' => $order->id,
                    'is_used' => true
                ]);
            }

            return $order;
        });
    }
}