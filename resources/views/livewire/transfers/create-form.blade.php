<?php

use Livewire\Volt\Component;
use App\Actions\InitiateTransfer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use App\Models\User;

new class extends Component {

    #[Validate('required|numeric|min:1')]
    public $amount = '';

    #[Validate('required')]
    #[Validate('string')]
    #[Validate('exists:users,username', message: 'User with the :attribute does not exist')]
    public $username = '';

    public User $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function create(InitiateTransfer $initiateTransfer)
    {
        $this->validate();

        try {
            $transfer = $initiateTransfer->execute(
                Auth::user(),
                floatval($this->amount),
                $this->username
            );

            $formattedAmount = to_money($this->amount, 1, '$');
            $this->dispatch('flash-success', message: "{$formattedAmount} sent to {$this->username}");
            $this->dispatch('transfer-created');

            $this->reset(['amount', 'username']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'Transfer failed');
        }
    }
}; ?>

<div>
    <div class="mb-8">
        <flux:heading size="lg">Transfer USD</flux:heading>
    </div>
    <form wire:submit="create">


        <div class="flex items-center gap-2">
            <flux:input.group>
                <flux:input
                    type="number"
                    step="0.01"
                    min="1"
                    name="amount"
                    wire:model="amount"
                    placeholder="Amount"
                    required
                />

                <flux:input.group.suffix>USD</flux:input.group.suffix>
            </flux:input.group>

            <flux:icon name="move-right"/>

            <flux:input
                type="text"
                steps=".01"
                name="username"
                wire:model="username"
                placeholder="Username"
                required
            />
        </div>

        <flux:error name="amount" />
        <flux:error name="username" />

        <div class="bg-gray-100 my-8 rounded-lg px-4 py-4 space-y-1">
            <div class="flex justify-between">
                <flux:heading>Balance</flux:heading>
                <flux:heading>{{ to_money($user->main_balance, 1, '$') }}</flux:heading>
            </div>
        </div>

        <div class="flex">
            <flux:spacer/>

            <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Proceed</flux:button>
        </div>
    </form>
</div>
