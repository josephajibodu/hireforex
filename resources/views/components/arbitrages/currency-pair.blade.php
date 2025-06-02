@props([
    'currencyPair',
    'order' => null
])

<div class="border rounded-lg mt-3.5 p-5 overflow-hidden" {{ $attributes }}>
    <div class="mb-5 flex flex-row items-center justify-between  gap-y-2 border-b border-dashed border-slate-300/70 pb-5">

        <div class="sm:text-center sm:ml-4 sm:text-left">
            <div class="text-sm font-medium">
                {{ $currencyPair->name }}
            </div>
        </div>

        <flux:badge :color="$currencyPair->status->getFluxColor()">{{ $currencyPair->status === \App\Enums\BinaryStatus::Open ? 'Trade Open' : 'Closed (Reopens at 12AM)' }}</flux:badge>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 text-center sm:flex-row">
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Profit Margin</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ $currencyPair->margin }}%</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Duration</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ $currencyPair->trade_duration }} hours</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Traded Capacity</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">${{ to_money($currencyPair->current_capacity, 1, false) }} out of ${{ to_money($currencyPair->daily_capacity, 1, false) }}</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            @if($currencyPair->status === \App\Enums\BinaryStatus::Open)
                <flux:button
                    class="cursor-pointer"
                    variant="primary"
                    x-data=""
                    x-on:click="$wire.viewCurrencyPair({{ $currencyPair->id }})"
                >Trade Now</flux:button>
            @else
                <flux:button
                    class="cursor-pointer disabled:opacity-25!"
                    disabled
                    variant="primary"
                    x-data=""
                >Trade Now</flux:button>
            @endif
        </div>
    </div>
</div>