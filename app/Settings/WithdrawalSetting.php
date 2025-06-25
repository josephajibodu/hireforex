<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WithdrawalSetting extends Settings
{
    public float $withdrawal_fee_percentage = 10.0;
    public float $minimum_withdrawal_amount = 10.0;
    public float $maximum_withdrawal_amount = 10000.0;
    public int $withdrawal_processing_time_hours = 1;
    public bool $withdrawal_enabled = true;

    public static function group(): string
    {
        return 'withdrawal';
    }
}
