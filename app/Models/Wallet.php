<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $main_balance
 * @property int $reserve_balance
 * @property int $trading_balance
 * @property int $bonus_balance
 * @property int $withdrawal_balance
 *
 * @property Carbon $created_at Timestamp when the wallet was created
 * @property Carbon $updated_at Timestamp when the wallet was last updated
 *
 * @property-read User $user
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance'
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}