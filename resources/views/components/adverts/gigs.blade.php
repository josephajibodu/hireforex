@props([
    'advert',
    'order' => null
])

<div class="border rounded-lg mt-3.5 p-5 cursor-pointer overflow-hidden {{ $advert->id === $order?->sell_advert_id ? 'border-primary' : 'border-slate-300/70' }}" {{ $attributes }}>
    @if($advert->id === $order?->sell_advert_id)
        <span class="absolute top-0 right-0 md:right-[calc(50%)] bg-primary text-white px-2 py-1 rounded-bl md:rounded-b">Active Order</span>
    @endif
    <div class="mb-5 flex flex-row items-center justify-between  gap-y-2 border-b border-dashed border-slate-300/70 pb-5">

        <div class="sm:text-center sm:ml-4 sm:text-left">
            <div class="text-sm font-medium">
                {{ $advert->user->username }}
            </div>
            <div class="mt-0.5 text-slate-500 text-sm">
                Balance: {{ to_money($advert->user->trading_balance, 1, '$') }}
            </div>
        </div>

        @php
            $color = $advert->status === \App\Enums\SellAdvertStatus::Available ? 'green' : 'red';
        @endphp
        <flux:badge :color="$color">{{ $advert->status->getPublicLabel() }}</flux:badge>
    </div>
    <div class="grid grid-cols-2 {{ $advert->isUsdtPayment() ? 'md:grid-cols-3' : 'md:grid-cols-4' }} gap-y-3 text-center sm:flex-row">
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Payment</div>
            <div class="mt-1 flex items-center md:justify-center">
                @if($advert->isUsdtPayment())
                    <div class="text-sm font-medium">{{ $advert->type->getLabel() }}</div>
                @else
                    <div class="text-sm font-medium">{{ $advert->payment_method ?? '-' }}</div>
                @endif
            </div>
        </div>
        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Available To Buy</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ to_money($advert->available_balance, 100,  hideSymbol: true) }}</div>
            </div>
        </div>

        @if($advert->isLocalPayment())
            <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
                <div class="text-slate-500 text-sm">Rate</div>
                <div class="mt-1 flex items-center md:justify-center">
                    <div class="text-sm font-medium">{{ to_money(getCurrentRate()) }}/$</div>
                </div>
            </div>
        @endif

        <div class="flex-1 border-dashed text-start md:text-center last:border-0 sm:border-r">
            <div class="text-slate-500 text-sm">Amount Limit</div>
            <div class="mt-1 flex items-center md:justify-center">
                <div class="text-sm font-medium">{{ to_money($advert->minimum_sell) }} - {{ to_money($advert->max_sell) }}</div>
            </div>
        </div>
    </div>
</div>