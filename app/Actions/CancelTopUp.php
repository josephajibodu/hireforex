<?php

namespace App\Actions;

use App\Models\TopUp;
use Exception;
use Illuminate\Support\Facades\DB;

class CancelTopUp
{
    /**
     * Cancel a top-up request
     *
     * @throws Exception
     */
    public function execute(TopUp $topUp, string $adminNotes = null)
    {
        return DB::transaction(function () use ($topUp, $adminNotes) {
            // Check if top-up is already processed
            if ($topUp->isConfirmed()) {
                throw new Exception('Cannot cancel a confirmed top-up.');
            }

            if ($topUp->isCancelled()) {
                throw new Exception('This top-up has already been cancelled.');
            }

            // Update top-up status to cancelled
            $topUp->update([
                'status' => TopUp::STATUS_CANCELLED,
                'admin_notes' => $adminNotes,
            ]);

            return $topUp;
        });
    }
}
