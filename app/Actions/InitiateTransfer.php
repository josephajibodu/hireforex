<?php

namespace App\Actions;

use App\Enums\TransferStatus;
use App\Models\Transfer;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class InitiateTransfer
{
    /**
     * Initiate a transfer between users
     *
     * @throws Exception
     */
    public function execute(User $sender, float $amount, string $recipientUsername, string $notes = '')
    {
        return DB::transaction(function () use ($sender, $amount, $recipientUsername, $notes) {
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
            if (! $sender->hasSufficientBalance($amount)) {
                throw new Exception('Insufficient balance.');
            }

            // Debit sender's main wallet
            $sender->debit($amount, "Transfer to {$recipientUsername}");

            // Create transfer record
            $transfer = Transfer::query()->create([
                'user_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => (int) ($amount * 100),
                'status' => TransferStatus::Completed,
                'reference' => 'TRF-' . strtoupper(uniqid()),
                'notes' => $notes,
            ]);

            // Credit recipient's main wallet
            $recipient->credit($amount, "Transfer from {$sender->username}");

            return $transfer;
        });
    }
}
