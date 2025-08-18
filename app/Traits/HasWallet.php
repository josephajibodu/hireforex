<?php

namespace App\Traits;

use App\Enums\WalletActivityStatus;
use App\Enums\WalletType;
use App\Models\Wallet;
use App\Models\WalletActivity;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @property Wallet $wallet
 * @property-read float $main_balance
 * @property-read float $reserve_balance
 * @property-read float $trading_balance
 * @property-read float $withdrawal_balance
 * @property-read float $bonus_balance
 */
trait HasWallet
{
    /**
     * The base unit for wallet balance
     *
     */
    public const BALANCE_UNIT = 100;

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'user_id');
    }

    /**
     * Get the user's balance in Naira.
     */
    public function mainBalance(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->wallet ? $this->wallet->main_balance / static::BALANCE_UNIT : 0,
        );
    }

    /**
     * Credit any of the users account
     *
     * @throws Exception
     */
    public function credit(float $amount, string $reason = ''): WalletTransaction
    {
        return WalletService::forUser($this)->credit(WalletType::from("main_balance"), $amount, $reason);
    }

    /**
     * Debit any of the users account
     *
     * @throws Exception
     */
    public function debit(float $amount, string $reason = ''): WalletTransaction
    {
        return WalletService::forUser($this)->debit(WalletType::from("main_balance"), $amount, $reason);
    }

    /*
     * Check for sufficient balance
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->wallet && $this->main_balance >= $amount;
    }
}
