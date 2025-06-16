<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public string $bybit_uid;
    public string $usdt_trc;
    public string $usdt_bep;

    public static function group(): string
    {
        return 'general';
    }
}