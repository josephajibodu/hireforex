<?php

namespace App\Traits;

use App\Models\CryptoTrade;
use App\Models\Trade;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasCryptoTrades
{
    public function cryptoTrades(): HasMany
    {
        return $this->hasMany(CryptoTrade::class);
    }
}
