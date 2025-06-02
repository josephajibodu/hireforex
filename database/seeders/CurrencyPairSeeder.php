<?php

namespace Database\Seeders;

use App\Models\CurrencyPair;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencyPairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CurrencyPair::factory()->count(4)->create();
    }
}
