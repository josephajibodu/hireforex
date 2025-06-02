<?php

namespace App\Actions;

use App\Enums\WalletType;
use App\Enums\WithdrawalStatus;
use App\Models\PriceSchedule;
use App\Models\User;
use App\Models\CryptoTrade;
use App\Models\Withdrawal;
use App\Settings\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InitiateWithdrawal
{
    /**
     * Calculate withdrawal fee
     */
    protected function calculateFee(float $amount): float
    {
        $feePercentage = getWithdrawalFeePercentage();

        return $amount * $feePercentage;
    }

    /**
     * Initiate a withdrawal
     *
     * @throws Exception
     */
    public function execute(
        User  $user,
        float $coinAmount,
    ) {
        return DB::transaction(function () use (
            $user,
            $coinAmount,
        ) {
            // Check user has sufficient balance
            if (! $user->hasSufficientBalance(WalletType::Withdrawal, $coinAmount)) {
                throw new Exception('Insufficient balance.');
            }

            $currentRate = app(GeneralSetting::class)->usd_rate;

            $fiatAmount = $currentRate * $coinAmount;
            $fiatFee = $this->calculateFee($fiatAmount);
            $fiatAmountAfterFee = $fiatAmount - $fiatFee;

            // Ensure amount after fee is positive
            if ($fiatAmountAfterFee <= 0) {
                throw new Exception('Invalid amount');
            }

            // Debit user's main wallet
            $user->debit(
                WalletType::Withdrawal,
                $coinAmount,
                'Funds withdrawal to Naira'
            );

            $reference = Str::uuid()->toString();

            return Withdrawal::query()->create([
                'user_id' => $user->id,
                'reference' => $reference,
                'amount' => (int) ($coinAmount * 100),
                'amount_sent' => $fiatAmount,
                'fee' => $fiatFee,
                'rate' => $currentRate,
                'amount_payable' => $fiatAmountAfterFee,
                'status' => WithdrawalStatus::PENDING,
                'bank_name' => $user->bank_name,
                'bank_account_name' => $user->bank_account_name,
                'bank_account_number' => $user->bank_account_number,
                'metadata' => null,
            ]);
        });
    }
}
