<?php

namespace App\Actions;

use App\Actions\Leaderboard\UpdateLeaderboard;
use App\Enums\ReferralStatus;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class CheckReferrer
{
    public function execute(User $user, ?string $code): void
    {
        // If the referrer username is provided, use it to find the user
        $referrer = null;

        if (! is_null($code)) {
            $referrer = User::query()->where('referral_code', $code)->first();
        }

        if (! $referrer) {
            // Get the referral code from the cookie
            $cookieKey = get_referral_key();
            $referralCode = request()->cookie($cookieKey);

            if (!$referralCode) {
                return;
            }

            // Clear the cookie after extraction
            Cookie::queue(Cookie::forget($cookieKey));

            // Find the user associated with the referral code
            $referrer = User::query()->where('referral_code', $referralCode)->first();
        }

        if (! $referrer) {
            return;
        }

        $this->createReferralRecord($referrer, $user);
    }

    /**
     * Create a referral record.
     */
    protected function createReferralRecord(User $referrer, User $referredUser): void
    {
        Referral::query()->create([
            'user_id' => $referrer->id,
            'referred_user_id' => $referredUser->id,
            'bonus' => 0,
            'status' => ReferralStatus::Pending,
        ]);
    }
}