<?php

namespace Database\Seeders;

use App\Actions\CreateSellAdvert;
use App\Enums\KYCStatus;
use App\Enums\WalletType;
use App\Models\Order;
use App\Models\SellAdvert;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellAdvertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(CreateSellAdvert $createSellAdvert): void
    {

        DB::transaction(function () use ($createSellAdvert) {
            foreach ([1, 2, 3, 4, 5] as $item) {
                // Create primary seller
                $seller = User::factory()->create();

                $seller->credit(WalletType::Main, 10_000, 'registration bonus');

                // Create sell advert for the seller
                $createSellAdvert($seller, [
                    'amount' => 500,
                    'rate' => 1500,
                    'min_amount' => 10,
                    'max_amount' => 500,
                    'terms' => 'Standard selling terms',
                ]);
            }
        });
    }
}
