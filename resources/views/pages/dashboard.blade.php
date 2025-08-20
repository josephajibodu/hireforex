<x-layouts.app>
    @section('title', 'Dashboard')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Welcome, {{ ucfirst(auth()->user()->first_name) }}</flux:heading>

        <flux:subheading size="lg" class="mb-6">Your HireForex Dashboard</flux:subheading>
        <div class="mt-8">
            <p class="text-sm text-gray-500 mb-3 text-center">Currently available in</p>
            <div class="flex justify-center items-center gap-4">
                <img class="h-6 rounded-md" src="https://flagcdn.com/ng.svg" alt="Nigeria flag" title="Nigeria">
                <img class="h-6 rounded-md" src="https://flagcdn.com/za.svg" alt="South Africa flag" title="South Africa">
                <img class="h-6 rounded-md" src="https://flagcdn.com/ke.svg" alt="Kenya flag" title="Kenya">
                <img class="h-6 rounded-md" src="https://flagcdn.com/gh.svg" alt="Ghana flag" title="Ghana">
            </div>
        </div>
        <flux:separator variant="subtle" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">USDT Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ number_format(auth()->user()->main_balance ?? 0, 2) }} USDT</h2>
                    <p class="text-xs text-gray-400">Available balance for hiring traders and withdrawals</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="wallet" />
                    </div>
                </div>
            </div>

            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Active Trades</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ auth()->user()->activeTrades()->count() }}</h2>
                    <p class="text-xs text-gray-400">Currently active forex trades</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="chart-candlestick" />
                    </div>
                </div>
            </div>
        </div>

        @include('pages.partials.quicklinks')

        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Available Traders</flux:heading>

                <flux:button
                    href="{{ route('traders.index') }}"
                    wire:navigate="true"
                >
                    View All
                </flux:button>
            </div>

            <livewire:traders.available-traders limit="10" />
        </div>

        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Active Trades</flux:heading>

                <flux:button
                    href="{{ route('trades.active') }}"
                    wire:navigate="true"
                >
                    View All
                </flux:button>
            </div>

            <livewire:trades.active-trades limit="4" />
        </div>

        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Latest Trade Orders</flux:heading>

                <flux:button
                    href="{{ route('trades.history') }}"
                    wire:navigate="true"
                >
                    View All
                </flux:button>
            </div>

            <livewire:trades.latest-orders limit="4" />
        </div>
    </div>
</x-layouts.app>