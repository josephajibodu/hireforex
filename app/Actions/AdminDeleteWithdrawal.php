<?php

namespace App\Actions;

use App\Models\Withdrawal;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminDeleteWithdrawal
{
    public function execute(Withdrawal $withdrawal, string $adminNotes = ''): Withdrawal
    {
        if ($withdrawal->isCompleted()) {
            throw new Exception("Completed withdrawals cannot be deleted.");
        }

        return DB::transaction(function () use ($withdrawal, $adminNotes) {
            // If withdrawal is pending, refund the user's balance
            if ($withdrawal->isPending()) {
                $withdrawal->user->credit($withdrawal->amount, "Withdrawal refund - {$withdrawal->reference}");
            }

            $withdrawal->update([
                'status' => 'cancelled',
                'admin_notes' => $adminNotes,
                'processed_at' => now(),
            ]);

            return $withdrawal;
        });
    }
}