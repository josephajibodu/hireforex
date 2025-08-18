<?php

use Livewire\Volt\Component;
use App\Models\Trader;
use App\Actions\HireTrader;
use Livewire\Attributes\Rule;

new class extends Component {
    public Trader $trader;
    public $amount = '';
    public $successMessage = '';
    public $errorMessage = '';

    public function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:' . $this->trader->min_capital,
                'max:' . $this->trader->available_volume,
            ]
        ];
    }

    public function hireTrader()
    {
        $this->validate();

        try {
            $hireTraderAction = new HireTrader();
            $trade = $hireTraderAction->execute(auth()->user(), $this->trader, (float) $this->amount);

            $this->successMessage = "Your trade has been successfully created! Your returns will be available when the duration time has elapsed. Please check your Active Trades section to view your trade details when they become available.";
            $this->amount = '';
            $this->errorMessage = '';

            // Redirect to active trades after successful creation
            $this->redirect(route('trades.active'));

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->successMessage = '';
        }
    }
}; ?>

<div>
    <form wire:submit="hireTrader" x-data="{ amount: {{ (float) ($amount ?? 0) }}, potential: {{ (float) $trader->potential_return }}, mbg: {{ (float) $trader->mbg_rate }}, duration: {{ (int) $trader->duration_days }}, format(v) { v = Number(v) || 0; return '$' + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }, potentialAmount() { return (Number(this.amount) || 0) * (Number(this.potential) / 100); }, mbgAmount() { return (Number(this.amount) || 0) * (Number(this.mbg) / 100); } }" class="space-y-4">
        <!-- Amount Input -->
        <div>
            <label for="amount" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                Amount to Trade (USDT)
            </label>
            <flux:input
                wire:model="amount"
                x-model.number="amount"
                id="amount"
                type="number"
                step="0.01"
                min="{{ $trader->min_capital }}"
                max="{{ $trader->available_volume }}"
                placeholder="Enter amount in USDT"
                class="w-full"
            />
            <p class="text-xs text-neutral-500 mt-1">
                Min: ${{ number_format($trader->min_capital, 2) }} | Max: ${{ number_format($trader->available_volume, 2) }}
            </p>
            @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Trade Summary (live, via Alpine) -->
        <div class="bg-gray-50 dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-3">Trade Summary</h3>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-neutral-600 dark:text-neutral-400">Your Investment:</span>
                    <span class="font-medium" x-text="format(amount)"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-neutral-600 dark:text-neutral-400">Potential Return:</span>
                    <span class="font-medium text-green-600 dark:text-green-400" x-text="format(potentialAmount())"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-neutral-600 dark:text-neutral-400">MBG Refund (if trade fails):</span>
                    <span class="font-medium text-blue-600 dark:text-blue-400" x-text="format(mbgAmount())"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-neutral-600 dark:text-neutral-400">Trade Duration:</span>
                    <span class="font-medium" x-text="duration + ' days'"></span>
                </div>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="text-blue-700 dark:text-blue-300 text-sm">Your Current Balance:</span>
                <span class="text-blue-800 dark:text-blue-200 font-semibold">
                    ${{ number_format(auth()->user()->main_balance ?? 0, 2) }} USDT
                </span>
            </div>
        </div>

        <!-- Submit Button -->
        <flux:button
            type="submit"
            variant="primary"
            class="w-full"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50"
        >
            <span wire:loading.remove>Trade</span>
            <span wire:loading>Processing...</span>
        </flux:button>
    </form>

    <!-- Success Message -->
    @if($successMessage)
        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-2">
                <flux:icon name="check-circle" class="w-5 h-5 text-green-500" />
                <p class="text-green-700 dark:text-green-300">{{ $successMessage }}</p>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if($errorMessage)
        <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center gap-2">
                <flux:icon name="circle-alert" class="w-5 h-5 text-red-500" />
                <p class="text-red-700 dark:text-red-300">{{ $errorMessage }}</p>
            </div>
        </div>
    @endif
</div>
