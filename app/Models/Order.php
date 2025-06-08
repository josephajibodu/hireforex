<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gift_card_id',
        'quantity',
        'total_amount',
        'delivery_time',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }

    public function giftCardUnits()
    {
        return $this->hasMany(GiftCardUnit::class);
    }
}