<?php

namespace App\Actions;

use App\Models\Withdrawal;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ProcessWithdrawal
{
    /**
     * Process a withdrawal request
     *
     * @throws Exception
     */
    public function execute(Withdrawal $withdrawal, string $adminNotes = null)
    {
        return DB::transaction(function () use ($withdrawal, $adminNotes) {
            // Check if withdrawal is already processed
            if ($withdrawal->isCompleted()) {
                throw new Exception('This withdrawal has already been processed.');
            }

            if ($withdrawal->isCancelled() || $withdrawal->isRejected()) {
                throw new Exception('Cannot process a cancelled or rejected withdrawal.');
            }

            // Get the user
            $user = $withdrawal->user;
            if (!$user) {
                throw new Exception('User not found for this withdrawal.');
            }

            // Calculate 3% fee
            $fee = $withdrawal->amount * 0.03;
            $amountPayable = $withdrawal->amount - $fee;

            // Check if user has sufficient balance
                                    if (!$user->hasSufficientBalance($withdrawal->amount)) {
                throw new Exception('Insufficient balance for withdrawal.');
            }

            // Deduct amount from user's wallet
                                    $user->debit($withdrawal->amount, 'Withdrawal processing');

            // Update withdrawal with fee details and mark as completed
            $withdrawal->update([
                'fee' => $fee,
                'amount_payable' => $amountPayable,
                'status' => 'completed',
                'admin_notes' => $adminNotes,
                'processed_at' => now(),
            ]);

            // Create wallet transaction record (if you have a WalletTransaction model)
            // This would track the debit transaction

            return $withdrawal;
        });
    }
}
