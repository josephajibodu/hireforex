<?php

namespace App\Actions;

use App\Actions\Leaderboard\UpdateLeaderboard;
use App\Enums\ReferralStatus;
use App\Enums\WalletType;
use App\Models\Referral;
use App\Models\User;
use App\Settings\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\Cookie;

class ProcessRegistrationBonus
{
    public function __construct(public GeneralSetting $generalSetting)
    {}

    public function execute(User $user): void
    {
        try {
            if ($this->generalSetting->enable_registration_bonus) {
                $user->credit(
                    WalletType::Bonus,
                    $this->generalSetting->registration_bonus,
                    "Registration Bonus"
                );
            }
        } catch (Exception $ex) {
            report($ex);
        }
    }
}