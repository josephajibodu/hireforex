<?php

namespace App\Actions;

use App\Exceptions\InsufficientFundsException;
use App\Models\User;
use App\Models\Withdrawal;
use App\Settings\GeneralSetting;
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
        // Validate minimum withdrawal amount
        $minAmount = app(GeneralSetting::class)->minimum_usdt_withdrawal ?? 10;

        if ($amount < $minAmount) {
            throw new Exception("Minimum withdrawal amount is $minAmount USDT");
        }

        // Check if user has sufficient balance
        if ($user->main_balance < $amount) {
            throw new InsufficientFundsException("Insufficient balance. You have {$user->main_balance} USDT available.");
        }

        // Calculate fee (10% of total amount)
        $fee = $amount * 0.10;
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