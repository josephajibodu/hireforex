<?php

namespace App\Models;

use App\Enums\WithdrawalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property float $amount Amount requested in USDT
 * @property float $fee 3% transaction fee
 * @property float $amount_payable Amount after fee deduction
 * @property string $withdrawal_method Either 'usdt_address' or 'bybit_uid'
 * @property string|null $usdt_address USDT wallet address
 * @property string|null $network_type BEP-20, TRC-20
 * @property string|null $bybit_uid Bybit UID
 * @property string $reference Unique reference number
 * @property WithdrawalStatus $status
 * @property string|null $admin_notes Admin notes for processing
 * @property Carbon|null $processed_at When withdrawal was processed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 */
class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'amount_payable',
        'withdrawal_method',
        'usdt_address',
        'network_type',
        'bybit_uid',
        'reference',
        'status',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'amount_payable' => 'decimal:2',
        'status' => WithdrawalStatus::class,
        'processed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique reference number
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'WTH' . strtoupper(uniqid());
        } while (static::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get the withdrawal destination (either USDT address or Bybit UID)
     */
    public function getDestinationAttribute(): string
    {
        return $this->withdrawal_method === 'usdt_address'
            ? $this->usdt_address
            : $this->bybit_uid;
    }

    /**
     * Get the withdrawal method label
     */
    public function getMethodLabelAttribute(): string
    {
        return $this->withdrawal_method === 'usdt_address'
            ? 'USDT Wallet Address'
            : 'Bybit UID';
    }

    /**
     * Check if withdrawal is pending
     */
    public function isPending(): bool
    {
        return $this->status === WithdrawalStatus::PENDING;
    }

    /**
     * Check if withdrawal is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === WithdrawalStatus::COMPLETED;
    }

    /**
     * Check if withdrawal is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === WithdrawalStatus::CANCELLED;
    }

    /**
     * Check if withdrawal is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === WithdrawalStatus::REJECTED;
    }
}