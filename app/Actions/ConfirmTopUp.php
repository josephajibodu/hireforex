<?php

namespace App\Actions;

use App\Models\TopUp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ConfirmTopUp
{
    /**
     * Confirm a top-up request and credit user's wallet
     *
     * @throws Exception
     */
    public function execute(TopUp $topUp, string $adminNotes = null)
    {
        return DB::transaction(function () use ($topUp, $adminNotes) {
            // Check if top-up is already processed
            if ($topUp->isConfirmed()) {
                throw new Exception('This top-up has already been confirmed.');
            }

            if ($topUp->isCancelled()) {
                throw new Exception('Cannot confirm a cancelled top-up.');
            }

            // Get the user
            $user = $topUp->user;
            if (!$user) {
                throw new Exception('User not found for this top-up.');
            }

                                    // Ensure user has a wallet (trait methods will handle this)

            // Credit user's wallet
                                    $user->credit($topUp->amount, 'Top-up confirmation');

            // Update top-up status to confirmed
            $topUp->update([
                'status' => TopUp::STATUS_CONFIRMED,
                'admin_notes' => $adminNotes,
            ]);

            // Create wallet transaction record (if you have a WalletTransaction model)
            // This would track the credit transaction

            return $topUp;
        });
    }
}
