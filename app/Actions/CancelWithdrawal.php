<?php

namespace App\Actions;

use App\Models\Withdrawal;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class CancelWithdrawal
{
    /**
     * Cancel a withdrawal request and refund user's wallet
     *
     * @throws Exception
     */
    public function execute(Withdrawal $withdrawal, string $adminNotes = null)
    {
        return DB::transaction(function () use ($withdrawal, $adminNotes) {
            // Check if withdrawal is already processed
            if ($withdrawal->isCompleted()) {
                throw new Exception('Cannot cancel a completed withdrawal.');
            }

            if ($withdrawal->isCancelled() || $withdrawal->isRejected()) {
                throw new Exception('This withdrawal has already been cancelled or rejected.');
            }

            // Get the user
            $user = $withdrawal->user;
            if (!$user) {
                throw new Exception('User not found for this withdrawal.');
            }

            // Refund the withdrawal amount back to user's wallet
            if (!$user->wallet) {
                $user->wallet()->create(['balance' => 0]);
            }
            $user->wallet->increment('balance', $withdrawal->amount);

            // Update withdrawal status to cancelled
            $withdrawal->update([
                'status' => 'cancelled',
                'admin_notes' => $adminNotes,
            ]);

            return $withdrawal;
        });
    }
}
