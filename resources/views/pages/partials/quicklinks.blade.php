<div>
    <div class="pt-4 mb-4 flex justify-between">
        <flux:heading class="text-xl! md:text-2xl!">Quick Links</flux:heading>
    </div>

    @php
    $quickLinks = [
        [
            'route' => route('traders.index'),
            'icon' => 'chart-candlestick',
            'label' => 'Hire Trader'
        ],

        [
            'route' => route('top-up.create'),
            'icon' => 'plus-circle',
            'label' => 'Top Up Balance'
        ],

        [
            'route' => route('trades.history'),
            'icon' => 'file-clock',
            'label' => 'Trade History'
        ],

        [
            'route' => route('trades.active'),
            'icon' => 'activity',
            'label' => 'Active Trades'
        ],

        [
            'route' => route('withdrawal.create'),
            'icon' => 'arrow-down-circle',
            'label' => 'Withdraw'
        ],
    ];
    @endphp

    <div class="flex gap-4 overflow-x-auto">
        @foreach ($quickLinks as $item)
            <a href="{{ $item['route'] }}" wire:navigate class="flex flex-col items-center justify-center rounded-lg border bg-gray-100 min-w-24 size-26 md:32 gap-2">
                <div class="size-8 md:size-12 text-white bg-brand-500 rounded-full grid place-items-center">
                    <flux:icon name="{{ $item['icon'] }}" class="size-5 md:size-6" />
                </div>
                <p class="text-sm text-zinc-800 h-[40px] text-center">{{ $item['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>
