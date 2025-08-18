<?php

namespace App\Actions;

use App\Models\AdminTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProcessAdminTransfer
{
    /**
     * Execute the admin transfer action
     */
    public function execute(AdminTransfer $adminTransfer): void
    {
        DB::transaction(function () use ($adminTransfer) {
            // Get the user who will receive the transfer
            $user = $adminTransfer->user;

            // Credit the user's wallet
            $user->credit($adminTransfer->amount, 'Admin transfer - ' . $adminTransfer->notes);

            // Update the transfer status
            $adminTransfer->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        });
    }
}
