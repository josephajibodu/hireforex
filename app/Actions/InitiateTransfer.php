<?php

namespace App\Actions;

use App\Enums\TransferStatus;
use App\Models\Transfer;
use App\Models\User;
use App\Enums\WalletType;
use Exception;
use Illuminate\Support\Facades\DB;

class InitiateTransfer
{
    /**
     * Initiate a transfer between users
     *
     * @throws Exception
     */
    public function execute(User $sender, float $amount, string $recipientUsername)
    {
        if (! $sender->kyc?->isCompleted()) {
            throw new Exception('Your identity must be verified before you can transfer to other accounts.');
        }

        return DB::transaction(function () use ($sender, $amount, $recipientUsername) {
            // Find the recipient
            $recipient = User::query()->where('username', $recipientUsername)->first();

            // Validate recipient exists
            if (!$recipient) {
                throw new Exception('Recipient user not found.');
            }

            // Prevent self-transfer
            if ($sender->id === $recipient->id) {
                throw new Exception('You cannot transfer funds to yourself.');
            }

            // Check sender has sufficient balance
            if (! $sender->hasSufficientBalance(WalletType::Main, $amount)) {
                throw new Exception('Insufficient balance.');
            }

            // Debit sender's main wallet
            $sender->debit(WalletType::Main, $amount, "Transfer to {$recipientUsername}");

            // Create transfer record
            $transfer = Transfer::query()->create([
                'user_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => (int)($amount * 100),
                'status' => TransferStatus::Pending,
            ]);

            // Credit recipient's reserve wallet
            $recipient->credit(
                WalletType::Reserve,
                $amount,
                "Transfer from {$sender->username}"
            );

            // Update transfer status to completed
            $transfer->update([
                'status' => TransferStatus::Completed,
            ]);

            return $transfer;
        });
    }
}
