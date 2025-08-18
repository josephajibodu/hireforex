<?php

namespace App\Actions;

use App\Models\Trade;
use App\Models\User;
use App\Models\Trader;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompleteTrade
{
    /**
     * Complete a trade when duration elapses
     *
     * @throws Exception
     */
    public function execute(Trade $trade)
    {
        return DB::transaction(function () use ($trade) {
            // Check if trade is already completed
            if ($trade->isCompleted() || $trade->isRefunded()) {
                throw new Exception('This trade has already been processed.');
            }

            // Check if trade duration has elapsed
            if (!$trade->hasDurationElapsed()) {
                throw new Exception('Trade duration has not elapsed yet.');
            }

            // Get user and trader
            $user = $trade->user;
            $trader = $trade->trader;

            if (!$user || !$trader) {
                throw new Exception('User or trader not found for this trade.');
            }

            // Simulate trade result (in real system, this would come from trader's actual performance)
            // For now, we'll use a simple random result
            $isWin = $this->simulateTradeResult($trader);

            if ($isWin) {
                // Trade won - calculate returns
                $returnAmount = $trade->amount * ($trade->potential_return / 100);

                // Credit user's wallet with returns
                $user->credit($returnAmount, 'Trade completion - returns');

                // Update trade status
                $trade->update([
                    'status' => Trade::STATUS_COMPLETED,
                    'completed_at' => now(),
                ]);

                // Increase trader's available volume back
                $trader->increment('available_volume', $trade->amount);

                // If trader was marked as unavailable, mark as available again
                if (!$trader->is_available) {
                    $trader->update(['is_available' => true]);
                }

            } else {
                // Trade lost - process MBG refund
                $refundAmount = $trade->amount * ($trade->mbg_rate / 100);

                // Credit user's wallet with refund
                $user->credit($refundAmount, 'Trade completion - MBG refund');

                // Update trade status
                $trade->update([
                    'status' => Trade::STATUS_REFUNDED,
                    'completed_at' => now(),
                ]);

                // Increase trader's available volume back
                $trader->increment('available_volume', $trade->amount);

                // If trader was marked as unavailable, mark as available again
                if (!$trader->is_available) {
                    $trader->update(['is_available' => true]);
                }
            }

            return $trade;
        });
    }

    /**
     * Simulate trade result based on trader's win rate
     * In a real system, this would come from actual trading performance
     */
    private function simulateTradeResult(Trader $trader): bool
    {
        $winRate = $trader->win_rate;
        $random = mt_rand(1, 100);

        return $random <= $winRate;
    }
}
