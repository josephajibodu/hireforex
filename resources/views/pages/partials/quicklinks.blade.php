<div>
    <div class="pt-4 mb-4 flex justify-between">
        <flux:heading class="text-xl! md:text-2xl!">Quick Links</flux:heading>
    </div>

    @php
    $quickLinks = [
        [
            'route' => route('trade-arbitrage.index'),
            'icon' => 'chart-candlestick',
            'label' => 'Arbitrage Market'
        ],
        [
            'route' => route('buy.usd'),
            'icon' => 'shopping-cart',
            'label' => 'Buy USD'
        ],
        [
            'route' => route('withdrawal.create'),
            'icon' => 'wallet',
            'label' => 'Withdraw Naira'
        ],
        [
            'route' => route('buy.history'),
            'icon' => 'bars-4',
            'label' => 'Order History'
        ],
        [
            'route' => route('settings.referrals'),
            'icon' => 'users',
            'label' => 'Refer A Friend'
        ],
        [
            'route' => route('payouts'),
            'icon' => 'circle-arrow-out-up-right',
            'label' => 'Payouts'
        ]
    ];
    @endphp

    <div class="flex gap-4 overflow-x-auto">
        @foreach ($quickLinks as $item)
            <a href="{{ $item['route'] }}" wire:navigate class="flex flex-col items-center justify-center rounded-lg border bg-gray-100 min-w-24 size-26 md:size-32 gap-2">
                <div class="size-8 md:size-12 text-white bg-brand-500 rounded-full grid place-items-center">
                    <flux:icon name="{{ $item['icon'] }}" class="size-5 md:size-6" />
                </div>
                <p class="text-sm text-zinc-800 h-[40px] text-center">{{ $item['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>