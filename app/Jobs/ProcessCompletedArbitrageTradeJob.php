<?php

namespace App\Jobs;

use App\Enums\TradeStatus;
use App\Enums\WalletType;
use App\Models\Trade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessCompletedArbitrageTradeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Trade $trade)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $trade = $this->trade->fresh();

            // Ensure the trade is still pending before marking it as completed
            if (!$trade || !$trade->isPending() || $trade->getTimeLeft() > 0) {
                return;
            }

            // Mark trade as completed
            $trade->update(['status' => TradeStatus::Completed]);

            // Credit user with ROI (profit + capital)
            $user = $trade->user;
            $user->credit(WalletType::Withdrawal, $trade->total_roi / 100, "Trade completed: ROI for {$trade->currency_pair_name}");

            // Optional: Log the trade completion for auditing
             logger()->info("Trade completed successfully", ['trade_id' => $trade->id]);
        });
    }
}
