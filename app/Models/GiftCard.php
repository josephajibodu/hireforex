<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftCard extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'resell_value' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(GiftCardUnit::class);
    }

    public function available(): HasMany
    {
        return $this->hasMany(GiftCardUnit::class)->where('is_used', false)->whereNull('order_id');
    }
}