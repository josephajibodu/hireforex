<?php

namespace App\Jobs;

use App\Enums\WalletActivityStatus;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletType;
use App\Models\WalletActivity;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class MoveReserveBalanceToMainJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var WalletTransaction[] $transactions */
        $transactions = WalletTransaction::query()
            ->with(['wallet.user'])
            ->where('destination', WalletType::Reserve)
            ->where('status', WalletTransactionStatus::Pending)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($transactions as $transaction) {
            WalletService::commitReserveToMain($transaction);
        }
    }
}
