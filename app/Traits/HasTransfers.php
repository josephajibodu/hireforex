<?php

namespace App\Traits;

use App\Models\Trade;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTransfers
{
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }
}
