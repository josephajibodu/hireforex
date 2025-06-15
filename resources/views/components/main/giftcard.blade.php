@props([
    'giftCard',
    'order' => null
])

<div class="border rounded-lg mt-3.5 p-5 overflow-hidden" {{ $attributes }}>
    <div class="mb-5 flex flex-row items-center justify-between gap-y-2 border-b border-dashed border-slate-300/70 pb-5">
        <div class="sm:ml-4 sm:text-left">
            <div class="text-sm font-medium">
                {{ $giftCard->name }}
            </div>
        </div>

        <flux:badge :color="$giftCard->is_available ? 'success' : 'danger'">
            {{ $giftCard->is_available ? 'Available' : 'Unavailable' }}
        </flux:badge>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 text-center sm:flex-row">
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Delivery Duration</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ $giftCard->delivery_duration }} hours</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Resell Value</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">${{ number_format($giftCard->resell_value, 2) }}</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Available</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ $giftCard->available->count() }} units</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            @if($giftCard->is_available)
                <flux:button
                    class="cursor-pointer"
                    variant="primary"
                    x-data=""
                    x-on:click="$wire.purchaseGiftCard({{ $giftCard->id }})"
                >Buy now ({{ to_money($giftCard->amount, hideSymbol: true) }} USDT)</flux:button>
            @else
                <flux:button
                    class="cursor-pointer disabled:opacity-25!"
                    disabled
                    variant="primary"
                    x-data=""
                >Buy now (35 USDT)</flux:button>
            @endif
        </div>
    </div>
</div>
