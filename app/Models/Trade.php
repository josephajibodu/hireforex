<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $trader_id
 * @property float $amount
 * @property float $potential_return
 * @property float $mbg_rate
 * @property string $status
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property \Carbon\Carbon $completed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trader_id',
        'amount',
        'potential_return',
        'mbg_rate',
        'status',
        'start_date',
        'end_date',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'potential_return' => 'decimal:2',
        'mbg_rate' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Trade statuses
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user who made this trade
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trader for this trade
     */
    public function trader(): BelongsTo
    {
        return $this->belongsTo(Trader::class);
    }

    /**
     * Get the potential return amount
     */
    public function getPotentialReturnAmountAttribute(): float
    {
        return $this->amount * ($this->potential_return / 100);
    }

    /**
     * Get the MBG refund amount
     */
    public function getMbgRefundAmountAttribute(): float
    {
        return $this->amount * ($this->mbg_rate / 100);
    }

    /**
     * Check if trade is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if trade is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if trade is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    /**
     * Check if trade is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if trade duration has elapsed
     */
    public function hasDurationElapsed(): bool
    {
        return $this->end_date && now()->greaterThanOrEqualTo($this->end_date);
    }

    /**
     * Get time remaining until trade completion
     */
    public function getTimeRemainingAttribute(): ?int
    {
        if (!$this->end_date || $this->status !== self::STATUS_ACTIVE) {
            return null;
        }

        $now = Carbon::now();
        $timeLeft = $now->diffInSeconds($this->end_date, false);

        return max(0, $timeLeft);
    }

    /**
     * Get the trade duration in days
     */
    public function getDurationDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Scope for active trades
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for completed trades
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for refunded trades
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }
}
