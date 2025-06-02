<?php

namespace App\Traits;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read Referral $referrer
 * @property-read Collection<Referral> $referrals
 */
trait HasReferrals
{
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'user_id');
    }

    public function referrer(): HasOne
    {
        return $this->hasOne(Referral::class, 'referred_user_id');
    }
}