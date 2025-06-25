<?php

namespace App\Actions;

use App\Models\Withdrawal;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminSettleWithdrawal
{
    public function execute(Withdrawal $withdrawal, string $adminNotes = ''): Withdrawal
    {
        if (!$withdrawal->isPending()) {
            throw new Exception("Only pending withdrawals can be settled.");
        }

        return DB::transaction(function () use ($withdrawal, $adminNotes) {
            $withdrawal->update([
                'status' => 'completed',
                'admin_notes' => $adminNotes,
                'processed_at' => now(),
            ]);

            return $withdrawal;
        });
    }
}