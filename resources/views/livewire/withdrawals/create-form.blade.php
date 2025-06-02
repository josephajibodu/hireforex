<?php

use Livewire\Volt\Component;
use App\Actions\InitiateWithdrawal;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

new class extends Component {

    public User $user;
    public float $withdrawal_fee = 0;

    public float $total_fee = 0;

    public $amount = '';

    public float $rate = 0;

    public float $totalAmount = 0;

    public float $amountPayable = 0;

    public function updatedAmount()
    {
        $this->totalAmount = $this->rate * floatval($this->amount ?? 0);
        $this->total_fee = $this->withdrawal_fee * floatval($this->totalAmount ?? 0);
        $this->amountPayable = $this->totalAmount - $this->total_fee;
    }

    public function mount(\App\Settings\GeneralSetting $generalSetting)
    {
        $this->rate = $generalSetting->usd_rate;
        $this->withdrawal_fee = getWithdrawalFeePercentage();
        $this->user = Auth::user();
    }

    public function create(InitiateWithdrawal $initiateWithdrawal)
    {
        try {
            $transfer = $initiateWithdrawal->execute(
                Auth::user(),
                floatval($this->amount),
            );

            $this->dispatch('flash-success', message: 'Payments will be processed within 24 hours or less');
            $this->dispatch('withdrawal-initiated');

            $this->reset(['amount', 'amountPayable', 'totalAmount', 'total_fee']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'Withdrawal failed');
        }
    }
}; ?>

<div>
    <div class="mb-8">
        <flux:heading size="lg">Withdrawal Form</flux:heading>
    </div>
    <form wire:submit="create">


        <div class="flex items-center gap-2">
            <flux:input.group>
                <flux:input
                    type="number"
                    step="0.01"
                    min="1"
                    name="amount"
                    wire:model.live.debounce="amount"
                    placeholder="Amount"
                    required
                />

                <flux:input.group.suffix>USD</flux:input.group.suffix>
            </flux:input.group>

            <flux:icon name="arrow-right-left"/>

            <flux:input.group>
                <flux:input
                        type="number"
                        steps=".01"
                        disabled
                        name="amountPayable"
                        wire:model="amountPayable"
                        placeholder="You get"
                        required
                />

                <flux:input.group.suffix>Naira</flux:input.group.suffix>
            </flux:input.group>
        </div>

        <div class="bg-gray-100 my-8 rounded-lg px-4 py-4 space-y-1">
            <div class="flex justify-between">
                <flux:heading>Withdrawal Balance</flux:heading>
                <flux:heading>{{ to_money($user->withdrawal_balance, 1, '$') }}</flux:heading>
            </div>
            <hr class="my-2" />
            <div class="flex justify-between">
                <flux:heading>Current Rate</flux:heading>
                <flux:heading>{{ to_money($rate) }}</flux:heading>
            </div>
            <hr class="my-2" />
            <div class="flex justify-between">
                <flux:subheading>Subtotal</flux:subheading>
                <flux:subheading>{{ to_money($totalAmount) }}</flux:subheading>
            </div>
            <div class="flex justify-between">
                <flux:subheading>Fee</flux:subheading>
                <flux:subheading> - {{ to_money($total_fee) }}</flux:subheading>
            </div>
            <div class="flex justify-between">
                <flux:heading size="lg">You receive</flux:heading>
                <flux:heading size="lg">{{ to_money($amountPayable) }}</flux:heading>
            </div>
        </div>

        <div class="flex">
            <flux:spacer/>

            <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Proceed</flux:button>
        </div>
    </form>
</div>
