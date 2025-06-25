<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('withdrawal.withdrawal_fee_percentage', 10.0);
        $this->migrator->add('withdrawal.minimum_withdrawal_amount', 10.0);
        $this->migrator->add('withdrawal.maximum_withdrawal_amount', 10000.0);
        $this->migrator->add('withdrawal.withdrawal_processing_time_hours', 1);
        $this->migrator->add('withdrawal.withdrawal_enabled', true);
    }
};
