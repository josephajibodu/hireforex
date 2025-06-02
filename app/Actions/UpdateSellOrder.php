<?php

namespace App\Actions;

use App\Enums\SellAdvertStatus;
use App\Enums\SellAdvertType;
use App\Models\SellAdvert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateSellOrder
{
    /**
     * Update the sell order.
     *
     * @throws Exception
     */
    public function execute(SellAdvert $sellAdvert, array $data): SellAdvert
    {
        if ($data['is_usdt'] && (!$data['wallet_address'] || !$data['network'])) {
            throw new Exception('Fill in your network type and wallet address to accept payment via USDT.');
        }

        return DB::transaction(function () use ($sellAdvert, $data) {
            $user = $sellAdvert->user;

            $sellAdvert->update([
                'minimum_sell' => $data['min_amount'],
                'max_sell' => $data['max_amount'],
                'terms' => $data['terms'],
                'payment_method' => $data['payment_method'],

                'type' => $data['is_usdt'] ? SellAdvertType::Usdt : SellAdvertType::Local,
                'network_type' => $data['network'],
                'wallet_address' => $data['wallet_address']
            ]);

            return $sellAdvert;
        });
    }
}