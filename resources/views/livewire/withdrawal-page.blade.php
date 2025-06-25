<?php

use Livewire\Volt\Component;
use App\Actions\InitiateUSDTWithdrawal;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Validate;

new class extends Component {

    #[Validate("required|numeric|min:10")]
    public $amount = '';

    #[Validate('required|string|in:usdt_address,bybit_uid')]
    public string $withdrawalMethod = 'usdt_address';

    #[Validate('required|string')]
    public string $destination = '';

    #[Validate('required|string|in:TRC-20,BEP-20')]
    public string $networkType = 'TRC-20';

    public User $user;
    public float $fee = 0;
    public float $amountPayable = 0;
    public float $feePercentage = 10; // 10% fee

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function updatedAmount()
    {
        if ($this->amount) {
            $this->fee = (floatval($this->amount) * $this->feePercentage) / 100;
            $this->amountPayable = floatval($this->amount) - $this->fee;
        } else {
            $this->fee = 0;
            $this->amountPayable = 0;
        }
    }

    public function updatedWithdrawalMethod()
    {
        $this->destination = '';
        $this->networkType = 'TRC-20';
    }

    public function create(InitiateUSDTWithdrawal $initiateUSDTWithdrawal)
    {
        $this->validate();

        try {
            $withdrawal = $initiateUSDTWithdrawal->execute(
                Auth::user(),
                floatval($this->amount),
                $this->withdrawalMethod,
                $this->destination,
                $this->withdrawalMethod === 'usdt_address' ? $this->networkType : null
            );

            $this->dispatch('flash-success', message: 'Withdrawal request submitted successfully. It will be processed within 1 hour.');
            $this->reset(['amount', 'destination', 'fee', 'amountPayable']);
        } catch (Exception $ex) {
            report($ex);
            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'Withdrawal failed');
        }
    }
}; ?>

<div>
    <flux:heading size="lg" class="mb-6">Withdrawal Form</flux:heading>

    <form wire:submit="create">
        <!-- Total Balance Display -->
        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6">
            <div class="flex justify-between items-center">
                <flux:heading>TOTAL BALANCE:</flux:heading>
                <flux:heading class="text-blue-600 dark:text-blue-400">
                    {{ number_format($user->main_balance, 2) }} USDT
                </flux:heading>
            </div>
        </div>

        <!-- Amount Input -->
        <div class="mb-6">
            <flux:field>
                <flux:label>WITHDRAW AMOUNT:</flux:label>
                <flux:input.group>
                    <flux:input
                        type="number"
                        step="0.01"
                        min="10"
                        name="amount"
                        wire:model.live.debounce="amount"
                        placeholder="Enter amount in USDT"
                        required
                    />
                    <flux:input.group.suffix>USDT</flux:input.group.suffix>
                </flux:input.group>
                <flux:error name="amount" />
            </flux:field>
        </div>

        <!-- Withdrawal Method Selection -->
        <div class="mb-6">
            <flux:field>
                <flux:label>Withdrawal Method:</flux:label>
                <flux:select wire:model.live="withdrawalMethod">
                    <option value="usdt_address">Via USDT Wallet Address</option>
                    <option value="bybit_uid">Via Bybit UID</option>
                </flux:select>
                <flux:error name="withdrawalMethod" />
            </flux:field>
        </div>

        <!-- USDT Address Section -->
        @if($withdrawalMethod === 'usdt_address')
            <div class="space-y-4 mb-6">
                <flux:field>
                    <flux:label>USDT ADDRESS:</flux:label>
                    <flux:input
                        type="text"
                        name="destination"
                        wire:model="destination"
                        placeholder="Enter your USDT wallet address"
                        required
                    />
                    <flux:error name="destination" />
                </flux:field>

                <flux:field>
                    <flux:label>Network Type:</flux:label>
                    <flux:select wire:model="networkType">
                        <option value="TRC-20">TRC-20</option>
                        <option value="BEP-20">BEP-20</option>
                    </flux:select>
                    <flux:error name="networkType" />
                </flux:field>
            </div>
        @endif

        <!-- Bybit UID Section -->
        @if($withdrawalMethod === 'bybit_uid')
            <div class="mb-6">
                <flux:field>
                    <flux:label>BYBIT UID:</flux:label>
                    <flux:input
                        type="text"
                        name="destination"
                        wire:model="destination"
                        placeholder="Enter your Bybit UID"
                        required
                    />
                    <flux:error name="destination" />
                </flux:field>
            </div>
        @endif

        <!-- Fee Calculation -->
        @if($amount)
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6 space-y-2">
                <div class="flex justify-between">
                    <flux:subheading>Amount:</flux:subheading>
                    <flux:subheading>{{ number_format($amount, 2) }} USDT</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:subheading>Fee ({{ $feePercentage }}%):</flux:subheading>
                    <flux:subheading class="text-red-600">-{{ number_format($fee, 2) }} USDT</flux:subheading>
                </div>
                <hr class="my-2">
                <div class="flex justify-between">
                    <flux:heading>You will receive:</flux:heading>
                    <flux:heading class="text-green-600">{{ number_format($amountPayable, 2) }} USDT</flux:heading>
                </div>
            </div>
        @endif

        <!-- Submit Button -->
        <div class="flex justify-end">
            <flux:button
                variant="primary"
                wire:loading.attr="disabled"
                type="submit"
                class="w-full"
            >
                <span wire:loading.remove>WITHDRAW</span>
                <span wire:loading>Processing...</span>
            </flux:button>
        </div>
    </form>
</div>
