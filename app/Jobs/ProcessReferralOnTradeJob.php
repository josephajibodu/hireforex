<?php

namespace App\Jobs;

use App\Enums\ReferralStatus;
use App\Enums\WalletType;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessReferralOnTradeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public float $amount)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $referralObj = $this->user->referrer;

        if (! $referralObj) {
            return;
        }

        $referrer = $referralObj->user;

        $referralBonus = is_referral_flat() ? get_referral_bonus() : get_referral_bonus() * $this->amount;

        $referrer->credit(WalletType::Main, $referralBonus, "Referral bonus for  {$this->user->username}");

        $referralObj->update([
            'bonus' => $referralBonus,
            'status' => ReferralStatus::Completed
        ]);
    }
}
