<?php

namespace App\Traits;

use App\Models\Trade;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasWithdrawals
{
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }
}
