<div>
    <div class="pt-4 mb-4 flex justify-between">
        <flux:heading class="text-xl! md:text-2xl!">Navigation</flux:heading>
    </div>

    @php
    $quickLinks = [
        [
            'route' => route('traders.index'),
            'icon' => 'chart-candlestick',
            'label' => 'Hire Trader'
        ],
        [
            'route' => route('transfer.create'),
            'icon' => 'send',
            'label' => 'Transfer Funds'
        ],
        [
            'route' => route('withdrawal.create'),
            'icon' => 'arrow-down-circle',
            'label' => 'Withdraw'
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
    ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach ($quickLinks as $item)
            <a href="{{ $item['route'] }}" wire:navigate class="group flex flex-col items-center justify-center text-center p-4 rounded-lg border bg-slate-50 hover:bg-white hover:border-brand-500 hover:shadow-lg transition-all duration-300 gap-3">
                <div class="size-12 text-white bg-brand-500 rounded-full grid place-items-center transition-transform duration-300 group-hover:scale-110">
                    <flux:icon name="{{ $item['icon'] }}" class="size-6" />
                </div>
                <p class="min-h-[40px] flex items-center font-semibold text-sm text-slate-700 leading-tight">
                    {{ $item['label'] }}
                </p>
            </a>
        @endforeach
    </div>

    <div class="mt-12">
        <div class="pt-4 mb-4">
            <flux:heading class="text-xl! md:text-2xl!">HireForex Community</flux:heading>
        </div>
        <div class="p-6 border rounded-lg bg-slate-50">
            <p class="mb-6 text-slate-600">
                Join our members community for quick support, guides, realtime Q&A, exclusive promos, and exciting giveaways.
            </p>

            @php
            // The 'icon' and 'color' keys have been removed from this array.
            $communityLinks = [
                [
                    'href' => 'https://whatsapp.com/channel/0029Vb6aV9i6mYPK3LpKgz3Y',
                    'label' => 'Join WhatsApp Channel',
                ],
                [
                    'href' => 'https://t.me/+-VVhyy8vgkFmODI0',
                    'label' => 'Join Telegram Channel',
                ],
                [
                    'href' => 'https://wa.link/1ei9gc',
                    'label' => 'Call HireForex',
                ],
                [
                    'href' => 'https://wa.link/1ei9gc',
                    'label' => 'Contact Support',
                ],
            ];
            @endphp

            <div class="flex flex-col gap-3">
                @foreach ($communityLinks as $link)
                    <a href="{{ $link['href'] }}" target="_blank" class="w-full p-3 font-semibold text-center text-slate-800 rounded-lg border bg-white hover:bg-slate-100 transition-colors">
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</div>
