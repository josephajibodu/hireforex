<?php

namespace App\Models;

use App\Enums\SellAdvertStatus;
use App\Enums\SellAdvertType;
use App\Enums\TransferStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property SellAdvertType $type
 * @property int $available_balance
 * @property int $remaining_balance
 * @property int $unit_price
 * @property int $minimum_sell
 * @property int $max_sell
 * @property SellAdvertStatus $status
 * @property boolean $is_published
 * @property string $bank_name
 * @property string $bank_account_name
 * @property string $bank_account_number
 * @property string $network_type
 * @property string $wallet_address
 * @property string $terms
 * @property string $payment_method
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 */
class SellAdvert extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => SellAdvertType::class,
            'status' => SellAdvertStatus::class
        ];
    }

    public function isUsdtPayment(): bool
    {
        return $this->type === SellAdvertType::Usdt;
    }

    public function isLocalPayment(): bool
    {
        return $this->type === SellAdvertType::Local;
    }

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}