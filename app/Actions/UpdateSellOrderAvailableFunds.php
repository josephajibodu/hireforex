<?php

namespace App\Actions;

use App\Enums\WalletType;
use App\Models\SellAdvert;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateSellOrderAvailableFunds
{
    /**
     * Update the sell order.
     *
     * @throws Exception
     */
    public function execute(SellAdvert $sellAdvert, array $data): SellAdvert
    {
        return DB::transaction(function () use ($sellAdvert, $data) {
            $user = $sellAdvert->user;

            $extraAmount = $data['amount'];
            $extraAmountInUnit = $extraAmount * 100;

            $totalAvailable = $sellAdvert->available_balance;
            $totalRemaining = $sellAdvert->remaining_balance;

            if ($data['funding_type'] === 'add') {

                if (! $user->hasSufficientBalance(WalletType::Main, $extraAmount)) {
                    throw new Exception('Insufficient balance to allocate to the existing sell order.');
                }

                $user->internalTransfers(WalletType::Main, WalletType::Trading, $extraAmount);

                $totalAvailable = $sellAdvert->available_balance + $extraAmountInUnit;
                $totalRemaining = $sellAdvert->remaining_balance + $extraAmountInUnit;

            } else {
                if ($extraAmountInUnit > $sellAdvert->available_balance) {
                    throw new Exception('You cannot remove more than you have available in your sell order.');
                }

                $user->internalTransfers(WalletType::Trading, WalletType::Main, $extraAmount, "Release funds from trading back to Main");

                $totalAvailable = $sellAdvert->available_balance - $extraAmountInUnit;
                $totalRemaining = $sellAdvert->remaining_balance - $extraAmountInUnit;
            }

            $sellAdvert->update([
                'available_balance' => $totalAvailable,
                'remaining_balance' => $totalRemaining,
            ]);

            return $sellAdvert;
        });
    }
}