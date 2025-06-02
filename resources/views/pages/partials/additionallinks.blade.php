<div>
    <div class="pt-4 mb-4 flex justify-between">
        <flux:heading class="text-xl! md:text-2xl!">Additional Links</flux:heading>
    </div>

    @php
        $additionalLinks = [
            ['icon' => 'cog', 'route' => route('settings.profile'), 'label' => __('Account Settings')],
            ['icon' => 'arrow-right-left', 'route' => route('transfer.create'), 'label' => __('Transfer USD')],
            ['icon' => 'scale', 'route' => route('dispute'), 'label' => __('Dispute')],
            ['icon' => 'coins', 'route' => route('buy-usdt.create'), 'label' => __('Withdraw USDT')],
            ['icon' => 'heart-handshake', 'route' => route('rewards'), 'label' => __("Race to Top 4")],
        ];
    @endphp

    <div class="flex gap-4 overflow-x-auto">
        @foreach ($additionalLinks as $item)
            <a href="{{ $item['route'] }}" wire:navigate class="flex flex-col items-center justify-center rounded-lg border bg-gray-100 min-w-24 size-26 md:size-32 gap-2">
                <div class="size-8 md:size-12 text-white bg-brand-500 rounded-full grid place-items-center">
                    <flux:icon name="{{ $item['icon'] }}" class="size-5 md:size-6" />
                </div>
                <p class="text-sm text-zinc-800 h-[40px] text-center">{{ $item['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>