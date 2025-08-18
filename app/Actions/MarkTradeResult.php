<?php

namespace App\Actions;

use App\Models\Trade;
use Illuminate\Support\Facades\DB;

class MarkTradeResult
{
    /**
     * Execute the mark trade result action
     */
    public function execute(Trade $trade, string $result, string $adminNotes = ''): void
    {
        DB::transaction(function () use ($trade, $result, $adminNotes) {
            $user = $trade->user;

            if ($result === 'win') {
                // Calculate the win amount (potential return)
                $winAmount = $trade->amount * ($trade->potential_return / 100);

                // Credit the user with the win amount
                $user->credit($winAmount, 'Trade WIN - ' . $trade->trader->name . ' - ' . $adminNotes);

                // Update trade status
                $trade->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

            } elseif ($result === 'loss') {
                // Calculate the MBG refund amount
                $refundAmount = $trade->amount * ($trade->mbg_rate / 100);

                // Credit the user with the MBG refund
                $user->credit($refundAmount, 'Trade LOSS - MBG Refund - ' . $trade->trader->name . ' - ' . $adminNotes);

                // Update trade status
                $trade->update([
                    'status' => 'refunded',
                    'completed_at' => now(),
                ]);
            }

            // Increase trader's available volume since trade is complete
            $trade->trader->increment('available_volume', $trade->amount);
        });
    }
}
