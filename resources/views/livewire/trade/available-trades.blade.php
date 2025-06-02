<?php

use Livewire\Volt\Component;
use App\Models\CurrencyPair;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateBuyOrder;
use App\Enums\BinaryStatus;
use Livewire\Attributes\Validate;
use App\Actions\CreateArbitrageTrade;

new class extends Component {
    use WithPagination;

    public CurrencyPair $selected_pair;

    #[Validate('required|numeric|min:1')]
    public float $amount;

    public float $roi = 0;

    public function updatedAmount()
    {
        $this->roi = floatval($this->amount ?? 0) * (1 + $this->selected_pair->margin / 100);
    }

    public function viewCurrencyPair(int $pairId)
    {
        $this->selected_pair = CurrencyPair::query()->findOrFail($pairId);

        if ($this->selected_pair->status === BinaryStatus::Close) {
            $this->dispatch(
                "flash-info",
                message: "{$this->selected_pair->name} Arbitrage market is currently closed. Reopens by 12AM."
            );

            return;
        }

        Flux::modal('confirm')->show();
    }

    public function create(CreateArbitrageTrade $createArbitrageTrade)
    {
        $this->validate();

        Flux::modal('confirm')->close();

        if (!$this->amount || !$this->selected_pair) {
            $this->dispatch(
                "flash-info",
                message: "Please select a valid Arbitrage pair and enter a valid amount"
            );

            return;
        };

        try {
            $user = Auth::user();

            $order = $createArbitrageTrade->execute($user, $this->selected_pair, $this->amount);

            $this->dispatch(
                "flash-success",
                message: "Trade for {$this->selected_pair->name} is now active. You can keep tabs on your Active trade page."
            );

            $this->reset(['amount', 'roi', 'selected_pair']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch("flash-error", message: $ex->getMessage() ?? 'Failed to create buy order');
            $this->reset(['amount', 'roi', 'selected_pair']);
        }
    }
}; ?>

<div>
    <flux:modal name="confirm" class="w-full md:max-w-screen-sm" variant="flyout">
        <div class="space-y-6">
            @if(!$selected_pair)
                <div>
                    <flux:heading size="lg">Arbitrage</flux:heading>
                    <flux:subheading></flux:subheading>
                </div>
            @else
                <div>
                    <flux:heading size="lg">{{ $selected_pair->name }}</flux:heading>
                    <flux:subheading>Earn {{ $selected_pair->margin }}% trading this currency pair.</flux:subheading>
                </div>

                <flux:input wire:model.live.debounce.1000="amount" type="number" step="0.01" label="Amount(USD)" placeholder="Amount(USD) to buy" />

                <div class="border p-4 rounded-lg">
                    <flux:subheading>Expected Returns</flux:subheading>
                    <flux:heading size="xl">{{ to_money($roi, 1, '$') }}</flux:heading>
                </div>

                <div class="flex">
                    <flux:spacer />

                    <flux:button variant="primary" wire:loading.attr="disabled" wire:click="create">Start Trade</flux:button>
                </div>
            @endif
        </div>
    </flux:modal>

    <!-- Available Dealers -->
    <div class="mt-8">
        <div class="flex flex-col gap-2 mb-2">
            <flux:heading class="text-xl! md:text-2xl!">Arbitrage Market</flux:heading>
            <div class="flex gap-2 justify-between">
                <flux:button icon-trailing="activity" href="{{ route('trade-arbitrage.active_trades') }}">
                    My Active Arbitrage
                </flux:button>
                <flux:button icon-trailing="align-start-horizontal" href="{{ route('trade-arbitrage.all_trades') }}">
                    All Trades
                </flux:button>
            </div>
        </div>
        @php
            $currencyPairs = CurrencyPair::query()
                ->paginate(10);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse($currencyPairs as $pair)
                <x-arbitrages.currency-pair :currency-pair="$pair" />
            @empty
                <div class="flex flex-col justify-center items-center col-span-full py-12">
                    <flux:icon name="hand-coins" class="size-12 text-gray-300 mb-4" />
                    <flux:subheading size="lg">There are currently no currency pair available</flux:subheading>
                </div>
            @endforelse
        </div>
    </div>

    <div class="my-4">
        {{ $currencyPairs->links() }}
    </div>
</div>
