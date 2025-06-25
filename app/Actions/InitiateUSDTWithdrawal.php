<?php

namespace App\Actions;

use App\Exceptions\InsufficientFundsException;
use App\Models\User;
use App\Models\Withdrawal;
use App\Settings\WithdrawalSetting;
use Exception;
use Illuminate\Support\Facades\DB;

class InitiateUSDTWithdrawal
{
    public function execute(
        User $user,
        float $amount,
        string $withdrawalMethod,
        string $destination,
        ?string $networkType = null
    ): Withdrawal {
        $withdrawalSettings = app(WithdrawalSetting::class);

        // Check if withdrawals are enabled
        if (!$withdrawalSettings->withdrawal_enabled) {
            throw new Exception("Withdrawals are currently disabled.");
        }

        // Validate minimum withdrawal amount
        if ($amount < $withdrawalSettings->minimum_withdrawal_amount) {
            throw new Exception("Minimum withdrawal amount is {$withdrawalSettings->minimum_withdrawal_amount} USDT");
        }

        // Validate maximum withdrawal amount
        if ($amount > $withdrawalSettings->maximum_withdrawal_amount) {
            throw new Exception("Maximum withdrawal amount is {$withdrawalSettings->maximum_withdrawal_amount} USDT");
        }

        // Check if user has sufficient balance
        if ($user->main_balance < $amount) {
            throw new InsufficientFundsException("Insufficient balance. You have {$user->main_balance} USDT available.");
        }

        // Calculate fee based on settings
        $fee = $amount * ($withdrawalSettings->withdrawal_fee_percentage / 100);
        $amountPayable = $amount - $fee;

        return DB::transaction(function () use ($user, $amount, $fee, $amountPayable, $withdrawalMethod, $destination, $networkType) {
            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'fee' => $fee,
                'amount_payable' => $amountPayable,
                'withdrawal_method' => $withdrawalMethod,
                'usdt_address' => $withdrawalMethod === 'usdt_address' ? $destination : null,
                'network_type' => $networkType,
                'bybit_uid' => $withdrawalMethod === 'bybit_uid' ? $destination : null,
                'reference' => Withdrawal::generateReference(),
                'status' => 'pending',
            ]);

            // Deduct amount from user's main balance
            $user->debit($amount, "USDT withdrawal - {$withdrawal->reference}");

            return $withdrawal;
        });
    }
}
