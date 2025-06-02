<?php

namespace Database\Factories;

use App\Models\CurrencyPair;
use App\Enums\BinaryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CurrencyPair>
 */
class CurrencyPairFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurrencyPair::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = [
            'Belize Dollar (BZ$) / Brazil Real (R$)',
            'Guyana Dollar (GYD) / Laos Kip (LAK)',
            'US Dollar (USD) / Euro (EUR)',
            'British Pound (GBP) / Canadian Dollar (CAD)',
        ];

        $capacity = [200, 500, 1000, 1500];
        $currentCapacity = 0;

        return [
            'name' => fake()->randomElement($currencies),
            'margin' => fake()->numberBetween(5, 25),
            'trade_duration' => 8,
            'daily_capacity' => fake()->randomElement($capacity),
            'current_capacity' => $currentCapacity,
            'status' => BinaryStatus::Open,
        ];
    }
}
