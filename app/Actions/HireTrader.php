<?php

namespace App\Actions;

use App\Models\Trader;
use App\Models\Trade;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HireTrader
{
    /**
     * Hire a trader for a user
     *
     * @throws Exception
     */
    public function execute(User $user, Trader $trader, float $amount)
    {
        return DB::transaction(function () use ($user, $trader, $amount) {
            // Check if user has sufficient balance
            if (!$user->wallet || $user->wallet->balance < $amount) {
                throw new Exception('You currently don\'t have enough funds in your HireForex wallet. Please top up your balance to proceed with hiring a forex trader.');
            }

            // Check if trader is available
            if (!$trader->is_available) {
                throw new Exception('This trader is currently not available.');
            }

            // Check if trader has sufficient volume
            if (!$trader->hasSufficientVolume($amount)) {
                throw new Exception('The trader available volume is currently insufficient, please choose another forex trader or input an amount that match the available trader\'s volume.');
            }

            // Check if capital meets minimum requirement
            if (!$trader->acceptsCapital($amount)) {
                throw new Exception('Your capital is below this trader\'s Minimum Acceptable Capital, please choose another trader that have a lower Minimum Acceptable Capital.');
            }

            // Calculate trade details
            $potentialReturn = $trader->potential_return;
            $mbgRate = $trader->mbg_rate;
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addDays($trader->duration_days);

            // Create the trade
            $trade = Trade::create([
                'user_id' => $user->id,
                'trader_id' => $trader->id,
                'amount' => $amount,
                'potential_return' => $potentialReturn,
                'mbg_rate' => $mbgRate,
                'status' => Trade::STATUS_ACTIVE,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // Deduct amount from user's wallet
            $user->wallet->decrement('balance', $amount);

            // Reduce trader's available volume
            $trader->decrement('available_volume', $amount);

            // If trader's volume is now 0, mark as unavailable
            if ($trader->available_volume <= 0) {
                $trader->update(['is_available' => false]);
            }

            return $trade;
        });
    }
}
