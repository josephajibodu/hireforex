<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public string $bybit_uid;
    public string $usdt_trc;
    public string $usdt_bep;
    public string $binance_uid;
    public float $minimum_usdt_withdrawal = 10.0;

    public static function group(): string
    {
        return 'general';
    }
}
