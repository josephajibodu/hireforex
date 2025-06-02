<?php

namespace App\Traits;

use App\Models\Trade;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTrades
{
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }
}
