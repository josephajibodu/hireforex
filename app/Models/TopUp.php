<?php

namespace App\Models;

use App\Enums\TopupMethod;
use App\Enums\TopupStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property string $method
 * @property string $status
 * @property string|null $screenshot
 * @property string|null $bybit_email
 * @property string|null $network
 * @property string|null $admin_notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class TopUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'status',
        'screenshot',
        'bybit_email',
        'network',
        'admin_notes',
        'reference'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => TopupStatus::class,
        'method' => TopupMethod::class,
    ];

    /**
     * Top-up methods
     */
    public const METHOD_BYBIT = 'bybit';
    public const METHOD_USDT = 'usdt';


    /**
     * Get the user who made this top-up request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if top-up is pending
     */
    public function isPending(): bool
    {
        return $this->status === TopupStatus::Pending;
    }

    /**
     * Check if top-up is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === TopupStatus::Completed;
    }

    /**
     * Check if top-up is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === TopupStatus::Cancelled;
    }

    /**
     * Check if this is a Bybit top-up
     */
    public function isBybit(): bool
    {
        return $this->method === self::METHOD_BYBIT;
    }

    /**
     * Check if this is a USDT top-up
     */
    public function isUsdt(): bool
    {
        return $this->method === self::METHOD_USDT;
    }

    /**
     * Scope for pending top-ups
     */
    public function scopePending($query)
    {
        return $query->where('status', TopupStatus::Pending);
    }

    /**
     * Scope for confirmed top-ups
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', TopupStatus::Completed);
    }
}
