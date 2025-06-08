<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftCardUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_card_id',
        'code',
        'order_id',
        'is_used'
    ];

    protected $casts = [
        'is_used' => 'boolean'
    ];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}