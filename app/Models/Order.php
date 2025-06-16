<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'delivery_time' => 'datetime',
        'status' => OrderStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function giftCard(): BelongsTo
    {
        return $this->belongsTo(GiftCard::class);
    }

    public function giftCardUnits(): HasMany
    {
        return $this->hasMany(GiftCardUnit::class);
    }

    public function getTimeLeft(): ?int
    {
        if (!$this->delivery_time || $this->status === OrderStatus::Completed || $this->status === OrderStatus::Cancelled) {
            return null;
        }

        $now = Carbon::now();
        $timeLeft = $now->diffInSeconds($this->delivery_time, false);

        return max(0, $timeLeft);
    }
}