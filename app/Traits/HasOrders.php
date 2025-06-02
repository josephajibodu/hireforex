<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\SellAdvert;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOrders
{
    public function sellAdvert(): HasOne
    {
        return $this->hasOne(SellAdvert::class, 'user_id');
    }

    public function buyOrders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function sellOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'sell_advert_id');
    }

    /** Helper methods */

    public function totalTradeCompleted(): float|int
    {
        $total = $this->buyOrders()->scopes('completed')
            ->sum('coin_amount');
        return $total / 100;
    }
}
