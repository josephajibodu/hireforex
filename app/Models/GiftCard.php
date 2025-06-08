<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'resell_value',
        'delivery_duration',
        'available_units',
        'is_available'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'resell_value' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function units()
    {
        return $this->hasMany(GiftCardUnit::class);
    }

    public function availableUnits()
    {
        return $this->hasMany(GiftCardUnit::class)->where('is_used', false)->whereNull('order_id');
    }
}