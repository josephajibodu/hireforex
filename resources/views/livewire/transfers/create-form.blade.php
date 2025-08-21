<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $recipient = '';
    #[Validate('required|numeric|min:0.01')]
    public $amount = '';
    #[Validate('required|string|min:3|max:255')]
    public string $notes = '';

    public function transfer()
    {
        $this->validate();

        try {
            app(\App\Actions\InitiateTransfer::class)
                ->execute(Auth::user(), (float) $this->amount, $this->recipient, $this->notes);

            $this->dispatch('flash-success', title: 'Transfer successful', message: 'Funds have been sent to the recipient.');
            $this->reset(['recipient', 'amount', 'notes']);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('flash-error', message: $e->getMessage() ?? 'Transfer failed.');
        }
    }
}; ?>

<div>
    <form wire:submit="transfer" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Recipient Username*</label>
            <flux:input wire:model.defer="recipient" placeholder="Enter recipient username" required />
            <flux:error name="recipient" />
        </div>
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Amount (USDT)*</label>
            <flux:input type="number" step="0.01" min="0.01" wire:model.defer="amount" placeholder="Enter amount" required />
            <flux:error name="amount" />
        </div>
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Transfer Notes*</label>
            <flux:textarea wire:model.defer="notes" rows="3" placeholder="e.g. profit share, refund, gift" required />
            <flux:error name="notes" />
        </div>

        <div class="flex items-center justify-between text-xs text-red-600 dark:text-red-400">
            <span>Double-check the recipient’s username. Transfers cannot be reversed.</span>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Transfer Now</span>
                <span wire:loading>Processing...</span>
            </flux:button>
        </div>
    </form>
</div>


