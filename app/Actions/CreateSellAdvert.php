<?php

namespace App\Actions;

use App\Enums\SellAdvertStatus;
use App\Enums\SellAdvertType;
use App\Enums\SystemPermissions;
use App\Enums\WalletType;
use App\Exceptions\InsufficientFundsException;
use App\Models\SellAdvert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use function Filament\authorize;

class CreateSellAdvert
{
    /**
     * Create a new sell order.
     *
     * @throws Exception
     */
    public function execute(User $user, array $data): SellAdvert
    {
        if (! $user->hasPermissionTo(SystemPermissions::CreateSellOrder)) {
            abort(403, 'Unauthorized access');
        }

        if ($user->sellAdvert) {
            throw new Exception('You already have a sell advert');
        }

        if (! $user->kyc?->isCompleted()) {
            throw new Exception('Your identity must be verified before you can create a sell order.');
        }

        if ($data['is_usdt'] && (!$data['wallet_address'] || !$data['network'])) {
            throw new Exception('Fill in your network type and wallet address to accept payment via USDT.');
        }

        return DB::transaction(function () use ($user, $data) {
            $amountToSell = floatval($data['amount']);

            if ($user->main_balance < $amountToSell) {
                throw new InsufficientFundsException('Insufficient balance.');
            }

            // Move funds to trading balance
            $user->internalTransfers(WalletType::Main, WalletType::Trading, $data['amount'], 'creating sell advert');

            return SellAdvert::query()->create([
                'user_id' => $user->id,
                'unit_price' => 1,
                'minimum_sell' => $data['min_amount'],
                'max_sell' => $data['max_amount'],
                'bank_name' => $user->bank_name,
                'bank_account_name' => $user->bank_account_name,
                'bank_account_number' => $user->bank_account_number,
                'terms' => $data['terms'],
                'payment_method' => $data['payment_method'],
                'available_balance' => $amountToSell * 100,
                'remaining_balance' => $amountToSell * 100,
                'status' => SellAdvertStatus::Available,

                'type' => $data['is_usdt'] ? SellAdvertType::Usdt : SellAdvertType::Local,
                'network_type' => $data['network'],
                'wallet_address' => $data['wallet_address']
            ]);
        });
    }
}