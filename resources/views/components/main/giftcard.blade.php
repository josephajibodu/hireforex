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
            <div class="text-xs text-slate-500">
                {{ $giftCard->description }}
            </div>
        </div>

        <flux:badge :color="$giftCard->is_available ? 'success' : 'danger'">
            {{ $giftCard->is_available ? 'Available' : 'Unavailable' }}
        </flux:badge>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 text-center sm:flex-row">
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Denomination</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">${{ number_format($giftCard->denomination, 2) }}</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Processing Time</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ $giftCard->processing_time }} minutes</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Stock</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ $giftCard->stock }} available</div>
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            @if($giftCard->is_available)
                <flux:button
                    class="cursor-pointer"
                    variant="primary"
                    x-data=""
                    x-on:click="$wire.purchaseGiftCard({{ $giftCard->id }})"
                >Purchase Now</flux:button>
            @else
                <flux:button
                    class="cursor-pointer disabled:opacity-25!"
                    disabled
                    variant="primary"
                    x-data=""
                >Purchase Now</flux:button>
            @endif
        </div>
    </div>
</div>