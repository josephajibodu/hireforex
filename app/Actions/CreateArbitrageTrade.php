<?php

namespace App\Actions;
namespace App\Actions;

use App\Enums\OrderStatus;
use App\Enums\TradeStatus;
use App\Enums\SellAdvertStatus;
use App\Enums\WalletType;
use App\Jobs\ProcessReferralOnTradeJob;
use App\Models\CurrencyPair;
use App\Models\Leaderboard;
use App\Models\Order;
use App\Models\PriceSchedule;
use App\Models\SellAdvert;
use App\Models\Trade;
use App\Models\User;
use App\Settings\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateArbitrageTrade
{
    public function __construct(public GeneralSetting $generalSetting)
    {}

    /**
     * Create a new buy order.
     *
     * @param User $user
     * @param CurrencyPair $currencyPair
     * @param float $amount
     * @return mixed
     * @throws Exception
     */
    public function execute(User $user, CurrencyPair $currencyPair, float $amount): Trade
    {
        if ($currencyPair->current_capacity >= $currencyPair->daily_capacity) {
            throw new Exception("Daily trade limit for this pair has already been reached.");
        }

        if ($amount + $currencyPair->current_capacity > $currencyPair->daily_capacity) {
            throw new Exception("Maximum daily trade allowed for this pair exceeded.");
        }

        if (! $user->kyc?->isCompleted()) {
            throw new Exception('Your identity must be verified before you can create an arbitrage trade.');
        }

        $minimum_trade_amount_on_reg = $this->generalSetting->minimum_trade_amount_on_reg;
        if ($user->totalTradeCompleted() < $minimum_trade_amount_on_reg && ! $user->hasSufficientBalance(WalletType::Main, $amount)) {
            throw new Exception("Insufficient fund in main balance. To use your bonus balance, please buy a total of $minimum_trade_amount_on_reg USD");
        }

        if (! $user->hasSufficientBalance([WalletType::Main, WalletType::Bonus], $amount)) {
            throw new Exception('Insufficient fund in your balance.');
        }

        // check if the user already has a trade for this currency pair
        $hasActiveTrade = $user->trades()->where('currency_pair_id', $currencyPair->id)
            ->scopes('active')->exists();

        if ($hasActiveTrade) {
            throw new Exception("You cannot start another trade for this pair until the active trade is completed.");
        }

        return DB::transaction(function () use ($user, $currencyPair, $amount) {

            $user->debitWithBonus($amount, "Arbitrage trading for pair {$currencyPair->name}");

            $amount = $amount * 100;
            $roi = $amount * (1 + $currencyPair->margin/100);

            $isFirstTrade = !$user->trades()->exists();
            $trade =  Trade::query()->create([
                'reference' => Str::uuid(),
                'user_id' => $user->id,
                'currency_pair_id' => $currencyPair->id,
                'currency_pair_name' => $currencyPair->name,
                'capital' => $amount,
                'total_roi' => $roi,
                'margin_applied' => $currencyPair->margin,
                'payment_time_limit' => $currencyPair->trade_duration,
                'status' => TradeStatus::Trading,
            ]);

            // Update the currency pair's daily capacity
            $currencyPair->increment('current_capacity', $amount / 100);

            if ($isFirstTrade) {
                dispatch(new ProcessReferralOnTradeJob($user, $amount / 100));
            }

            $leaderboard = Leaderboard::query()->where('user_id', $user->id)->first();
            if (! $leaderboard) {
                Leaderboard::query()->create([
                    'user_id' => $user->id,
                    'amount' => $amount
                ]);
            } else {
                $leaderboard->amount += $amount;
                $leaderboard->save();
            }

            return $trade;
        });
    }
}