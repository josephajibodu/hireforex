<?php

use Livewire\Volt\Component;
use App\Actions\InitiateUSDTWithdrawal;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Validate;
use App\Settings\GeneralSetting;

new class extends Component {

    #[Validate("required|numeric")]
    public $amount = '';

    #[Validate('required|string|in:TRC-20,BEP-20,ERC-20')]
    public string $network = 'TRC-20';

    public string $wallet_address = '';

    public User $user;

    public float $min;

    public float $usdt_withdrawal_fee;

    public float $total_fee = 0;

    public float $amountPayable = 0;

    public function mount(GeneralSetting $generalSetting)
    {
        $this->user = Auth::user();

        $this->min = $generalSetting->minimum_usdt_withdrawal;

        $this->usdt_withdrawal_fee = $generalSetting->usdt_withdrawal_fee;
    }

    public function updatedAmount()
    {
        $this->total_fee = ($this->usdt_withdrawal_fee / 100) * floatval($this->amount ?? 0);
        $this->amountPayable = floatval($this->amount ?? 0) - $this->total_fee;
    }

    public function create(InitiateUSDTWithdrawal $initiateUSDTWithdrawal)
    {
        $this->validate([
            'amount' => "required|numeric|min:{$this->min}",
            'network' => 'required|string|in:TRC-20,BEP-20,ERC-20',
            'wallet_address' => 'required|string',
        ]);

        try {
            $transfer = $initiateUSDTWithdrawal->execute(
                Auth::user(),
                floatval($this->amount),
                $this->wallet_address,
                $this->network
            );

            $this->dispatch('flash-success', message: 'Payments will be processed within 24 hours or less');
            $this->reset(['amount', 'network', 'wallet_address', 'total_fee', 'amountPayable']);

            $this->reset(['amount', 'network', 'wallet_address']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'USDT Withdrawal failed');
            $this->reset(['amount', 'network', 'wallet_address', 'total_fee', 'amountPayable']);
        }
    }
}; ?>

<div>
    <div class="mb-8">
        <flux:heading class="text-xl! md:text-2xl!">Buy USDT</flux:heading>
    </div>

    <form wire:confirm="Please confirm the network and wallet address is correct before proceeding" wire:submit="create">
        <flux:input.group class="mb-4">
            <flux:input
                type="number"
                step="0.01"
                min="1"
                name="amount"
                wire:model.live.debounce.1000="amount"
                placeholder="Min Amount: {{ $min }}"
                required
            />

            <flux:input.group.suffix>USD</flux:input.group.suffix>
        </flux:input.group>

        <flux:error name="amount" class="mb-4" />

        <flux:input.group>
            <flux:select
                name="network"
                wire:model.live.debounce="network"
                class="max-w-fit"
                required="true"
            >
                <flux:select.option>TRC-20</flux:select.option>
                <flux:select.option>BEP-20</flux:select.option>
                <flux:select.option>ERC-20</flux:select.option>
            </flux:select>

            <flux:input
                name="wallet_address"
                wire:model.live.debounce="wallet_address"
                placeholder="Wallet Address"
                type="text"
                required="true"
            />
        </flux:input.group>
        <flux:error name="network" />
        <flux:error name="wallet_address" />

        <div class="bg-gray-100 my-8 rounded-lg px-4 py-4 space-y-1">
            <div class="flex justify-between">
                <flux:heading>Withdrawal Balance</flux:heading>
                <flux:heading>{{ to_money($user->withdrawal_balance, 1 , '$') }}</flux:heading>
            </div>
            <hr class="my-2" />
            <div class="flex justify-between">
                <flux:subheading>Wallet Address</flux:subheading>
                <flux:subheading class="break-words whitespace-normal">{{ $wallet_address ?? '-' }}</flux:subheading>
            </div>
            <div class="flex justify-between">
                <flux:subheading>Network</flux:subheading>
                <flux:subheading>{{ $network ?? '-' }}</flux:subheading>
            </div>

            <hr class="my-2" />

            <div class="flex justify-between">
                <flux:subheading>Subtotal</flux:subheading>
                <flux:subheading>{{ to_money($amount, 1, '$') }}</flux:subheading>
            </div>

            <div class="flex justify-between">
                <flux:subheading>Fee</flux:subheading>
                <flux:subheading> - {{ to_money($total_fee, 1, '$') }}</flux:subheading>
            </div>

            <div class="flex justify-between">
                <flux:heading size="lg">You receive</flux:heading>
                <flux:heading size="lg">{{ to_money($amountPayable, 1, '$') }}</flux:heading>
            </div>
        </div>

        <div class="flex">
            <flux:spacer/>

            <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Proceed</flux:button>
        </div>
    </form>
</div>
