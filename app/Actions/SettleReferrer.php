<?php

namespace App\Actions;

use App\Actions\Leaderboard\UpdateLeaderboard;
use App\Enums\ReferralStatus;
use App\Models\User;

class SettleReferrer
{
    public function __construct(public UpdateLeaderboard $updateLeaderboard)
    {}

    public function handle(User $user): void
    {
        $referral = $user->referralReceived;

        if (! $referral) {
            return;
        }

        if ($referral->isSettled()) {
            return;
        }

        // check type if its earner or
        if (is_referral_flat()) {
            $referralBonus = get_referral_bonus();
        } else {
            $referralBonus = get_activation_cost() * get_referral_bonus();
        }

        $referrer = User::find($referral->user_id);

        $referrer->credit($referralBonus, "Referral Bonus for ($user->username)");

        $referral->update([
            'bonus' => $referralBonus * 100,
            'status' => ReferralStatus::COMPLETED,
        ]);

        // Invalidate referral cache for the referrer
        $referrer->refreshReferralCache();

        // Update leaderboard
        $this->updateLeaderboard->handle($referrer);
    }
}