<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.referral_type', 'percentage');
        $this->migrator->add('general.referral_bonus', 20);
        $this->migrator->add('general.withdrawal_fee', 5);
        $this->migrator->add('general.order_time_limit', 12);
        $this->migrator->add('general.community_links', []);
        $this->migrator->add('general.reserve_balance_duration', 1*60);
        $this->migrator->add('general.usd_rate', 1650);
        $this->migrator->add('general.enable_registration_bonus', true);
        $this->migrator->add('general.registration_bonus', 5);
    }
};
