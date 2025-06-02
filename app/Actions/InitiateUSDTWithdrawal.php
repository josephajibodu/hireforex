<?php

namespace App\Actions;

use App\Enums\CryptoTradeStatus;
use App\Enums\WalletType;
use App\Enums\WithdrawalStatus;
use App\Models\CryptoTrade;
use App\Models\PriceSchedule;
use App\Models\User;
use App\Models\Withdrawal;
use App\Settings\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InitiateUSDTWithdrawal
{
    public function __construct(public GeneralSetting $generalSetting)
    {}

    /**
     * Initiate a withdrawal
     *
     * @throws Exception
     */
    public function execute(
        User  $user,
        float $amount,
        string $wallet_address,
        string $network,
    ) {
        return DB::transaction(function () use (
            $user,
            $amount,
            $network,
            $wallet_address
        ) {
            // Check user has sufficient balance
            if (! $user->hasSufficientBalance(WalletType::Withdrawal, $amount)) {
                throw new Exception('Insufficient balance.');
            }

            // Ensure amount after fee is positive
            if ($amount < $this->generalSetting->minimum_usdt_withdrawal) {
                throw new Exception("Minimum amount is USD {$this->generalSetting->minimum_usdt_withdrawal}");
            }

            $usdtFee = $this->calculateFee($amount);
            $amountAfterFee = $amount - $usdtFee;

            // Ensure amount after fee is positive
            if ($amountAfterFee <= 0) {
                throw new Exception('Invalid amount');
            }

            // Debit user's main wallet
            $user->debit(
                WalletType::Withdrawal,
                $amount,
                'Funds withdrawal to USDT'
            );

            $reference = Str::uuid()->toString();

            return CryptoTrade::query()->create([
                'user_id' => $user->id,
                'reference' => $reference,
                'amount' => (int) ($amount * 100),
                'amount_sent' => (int) ($amountAfterFee * 100),
                'network' => $network,
                'fee' => $usdtFee,
                'wallet_address' => $wallet_address,
                'status' => CryptoTradeStatus::PENDING,
            ]);
        });
    }

    /**
     * Calculate withdrawal fee
     */
    protected function calculateFee(float $amount): float
    {
        $feePercentage = app(GeneralSetting::class)->usdt_withdrawal_fee / 100;

        return $amount * $feePercentage;
    }
}
