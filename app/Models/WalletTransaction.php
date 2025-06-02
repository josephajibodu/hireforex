<?php

namespace App\Models;

use App\Enums\ExternalWalletType;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property WalletType|ExternalWalletType $source
 * @property WalletType|ExternalWalletType $destination
 * @property string $type
 * @property string $reason
 * @property float $amount
 * @property WalletTransactionStatus $status
 * @property Carbon $expires_at
 *
 * @property-read Wallet $wallet
 */
class WalletTransaction extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => WalletTransactionStatus::class
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
