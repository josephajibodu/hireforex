<?php

namespace App\Actions;

use App\Jobs\ProcessCompletedArbitrageTradeJob;
use App\Models\Trade;
use Exception;
use Illuminate\Support\Facades\DB;

class MoveArbitrageTradesToQueue
{
    /**
     * Moves expired arbitrage trades to the processing queue.
     *
     * @throws Exception
     */
    public function execute(): void
    {
        Trade::query()->scopes('active')->cursor()->each(function (Trade $trade) {
            if ($trade->isPending() && $trade->getTimeLeft() === 0) {
                ProcessCompletedArbitrageTradeJob::dispatch($trade);
            }
        });
    }
}