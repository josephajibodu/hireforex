<?php

namespace App\Services;

use App\Enums\ExternalWalletType;
use App\Enums\WalletActivityStatus;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletType;
use App\Exceptions\InsufficientFundsException;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Settings\GeneralSetting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    /**
     * The base unit for wallet balance
     */
    public const BALANCE_UNIT = 100;

    protected ?Wallet $wallet = null;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Get a WalletService instance for a given user.
     */
    public static function forUser(User $user): self
    {
        return new self($user->wallet);
    }

    /**
     * Transfer funds between wallet balances.
     *
     * @throws Exception
     */
    public function transferFunds(WalletType $from, WalletType $to, float $amount, string $reason = ''): WalletTransaction
    {
        if (!$this->wallet) {
            throw new Exception("No wallet associated with this service instance.");
        }

        if ($amount <= 0) {
            throw new Exception("Amount must be greater than zero.");
        }

        // Convert to Base Unit
        $amount = $amount * self::BALANCE_UNIT;

        return DB::transaction(function () use ($from, $to, $amount, $reason) {
            if ($this->wallet->{$from->value} < $amount) {
                throw new InsufficientFundsException("Insufficient funds in {$from->getLabel()}.");
            }

            $this->wallet->decrement($from->value, $amount);
            $this->wallet->increment($to->value, $amount);

            return WalletTransaction::query()->create([
                'wallet_id' => $this->wallet->id,
                'source' => $from->value,
                'destination' => $to->value,

                'type' => 'transfer',
                'amount' => $amount,
                'reason' => $reason,
                'status' => WalletTransactionStatus::Committed
            ]);
        });
    }

    /**
     * Directly credit a balance.
     *
     * @throws Exception
     */
    public function credit(WalletType $to, float $amount, string $reason = ''): WalletTransaction
    {
        if (!$this->wallet) {
            throw new Exception("No wallet associated with this service instance.");
        }

        if ($amount <= 0) {
            throw new Exception("Amount must be greater than zero.");
        }

        // Convert to Base Unit
        $amount = $amount * self::BALANCE_UNIT;

        return DB::transaction(function () use ($to, $amount, $reason) {
            $this->wallet->increment($to->value, $amount);

            return WalletTransaction::query()->create([
                'wallet_id' => $this->wallet->id,
                'source' => ExternalWalletType::External->value,
                'destination' => $to->value,

                'type' => 'credit',
                'amount' => $amount,
                'reason' => $reason,
                'status' => WalletTransactionStatus::Committed
            ]);
        });
    }

    /**
     * Directly debit a balance.
     * @throws Exception
     */
    public function debit(WalletType $from, float $amount, string $reason = ''): WalletTransaction
    {
        if (!$this->wallet) {
            throw new Exception("No wallet associated with this service instance.");
        }

        if ($amount <= 0) {
            throw new Exception("Amount must be greater than zero.");
        }

        // Convert to Base Unit
        $amount = $amount * self::BALANCE_UNIT;

        return DB::transaction(function () use ($from, $amount, $reason) {
            if ($this->wallet->{$from->value} < $amount) {
                throw new InsufficientFundsException("Insufficient funds in {$from->getLabel()}.");
            }

            $this->wallet->decrement($from->value, $amount);

            return WalletTransaction::query()->create([
                'wallet_id' => $this->wallet->id,
                'source' => $from->value,
                'destination' => ExternalWalletType::External->value,

                'type' => 'debit',
                'amount' => $amount,
                'reason' => $reason,
                'status' => WalletTransactionStatus::Committed
            ]);
        });
    }

    /**
     * Directly credit the reserve balance.
     *
     * @throws Exception
     */
    public function creditReserve(float $amount, string $reason = ''): WalletTransaction
    {
        if (!$this->wallet) {
            throw new Exception("No wallet associated with this service instance.");
        }

        if ($amount <= 0) {
            throw new Exception("Amount must be greater than zero.");
        }

        // Convert to Base Unit
        $amount = $amount * self::BALANCE_UNIT;

        return DB::transaction(function () use ($amount, $reason) {
            $this->wallet->increment('reserve_balance', $amount);

            return WalletTransaction::query()->create([
                'wallet_id' => $this->wallet->id,
                'source' => ExternalWalletType::External->value,
                'destination' => WalletType::Reserve->value,

                'type' => 'credit',
                'amount' => $amount,
                'reason' => $reason,
                'status' => WalletTransactionStatus::Pending,
                'expires_at' => now()->addMinutes(app(GeneralSetting::class)->reserve_balance_duration)
            ]);
        });
    }

    /**
     * Move reserve balance to main balance after expiration.
     */
    public static function commitReserveToMain(WalletTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {
            if ($transaction->wallet->reserve_balance < $transaction->amount) {
                logger()->channel("wallet")->info("Balance in reserve not enough to meet the pending amount", [
                    "transaction" => $transaction->toArray(),
                    "wallet" => $transaction->wallet->toArray()
                ]);

                return;
            }

            $transaction->wallet->decrement('reserve_balance', $transaction->amount);
            $transaction->wallet->increment('main_balance', $transaction->amount);

            $transaction->update(['status' => WalletTransactionStatus::Committed]);

            WalletTransaction::query()->create([
                'wallet_id' => $transaction->wallet->id,
                'source' => WalletType::Reserve->value,
                'destination' => WalletType::Main->value,

                'type' => 'transfer',
                'amount' => $transaction->amount,
                'reason' => 'Reserve balance released to main balance',
                'status' => WalletTransactionStatus::Committed,
            ]);
        });
    }
}