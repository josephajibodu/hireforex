<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public string $referral_type;

    public float $referral_bonus;

    public int $order_time_limit;

    public array $community_links;

    public float $withdrawal_fee;

    public float $reserve_balance_duration;

    public float $usd_rate;

    public bool $enable_registration_bonus;

    public float $registration_bonus;

    public float $minimum_usdt_withdrawal;

    public float $usdt_withdrawal_fee;

    public float $minimum_trade_amount_on_reg;

    public static function group(): string
    {
        return 'general';
    }
}