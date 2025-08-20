<?php

namespace Database\Seeders;

use App\Models\Trader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TraderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = [
            [
                'name' => 'Profx',
                'experience_years' => 4,
                'favorite_pairs' => 'USD/JPY',
                'track_record' => 'WLWLW',
                'mbg_rate' => 100.00,
                'potential_return' => 120.00,
                'min_capital' => 30.00,
                'available_volume' => 2000.00,
                'duration_days' => 5,
                'is_available' => true,
            ],
            [
                'name' => 'BoldPip',
                'experience_years' => 4,
                'favorite_pairs' => 'USD/JPY',
                'track_record' => 'WLWLW',
                'mbg_rate' => 100.00,
                'potential_return' => 110.00,
                'min_capital' => 250.00,
                'available_volume' => 8000.00,
                'duration_days' => 4,
                'is_available' => true,
            ],
            [
                'name' => 'Pipsniper',
                'experience_years' => 3,
                'favorite_pairs' => 'XAU/USD',
                'track_record' => 'LLWLW',
                'mbg_rate' => 80.00,
                'potential_return' => 170.00,
                'min_capital' => 50.00,
                'available_volume' => 7000.00,
                'duration_days' => 6,
                'is_available' => true,
            ],
            [
                'name' => 'FXLion',
                'experience_years' => 3,
                'favorite_pairs' => 'GBP/USD',
                'track_record' => 'LLWLW',
                'mbg_rate' => 95.00,
                'potential_return' => 130.00,
                'min_capital' => 50.00,
                'available_volume' => 14000.00,
                'duration_days' => 7,
                'is_available' => true,
            ],
            [
                'name' => 'SmartSwinger',
                'experience_years' => 6,
                'favorite_pairs' => 'EUR/USD',
                'track_record' => 'WWWLW',
                'mbg_rate' => 100.00,
                'potential_return' => 122.00,
                'min_capital' => 100.00,
                'available_volume' => 4000.00,
                'duration_days' => 7,
                'is_available' => true,
            ],
        ];

        foreach ($traders as $traderData) {
            Trader::create($traderData);
        }
    }
}
