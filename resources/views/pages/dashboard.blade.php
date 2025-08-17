<x-layouts.app>
    @section('title', 'Dashboard')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Welcome, {{ ucfirst(auth()->user()->first_name) }}</flux:heading>

        <flux:subheading size="lg" class="mb-6">Your HireForex Dashboard</flux:subheading>

        <flux:separator variant="subtle" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <!-- USDT Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">USDT Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ number_format($user->wallet?->balance ?? 0, 2) }} USDT</h2>
                    <p class="text-xs text-gray-400">Available balance for hiring traders and withdrawals</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="wallet" />
                    </div>
                </div>
            </div>

            <!-- Active Trades Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Active Trades</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ $user->activeTrades()->count() }}</h2>
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

        <!-- Available Traders -->
        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Available Traders</flux:heading>

                <flux:button
                    href="{{ route('traders.index') }}"
                    wire:navigate="true"
                    size="sm"
                >
                    View All
                </flux:button>
            </div>

            <livewire:traders.available-traders limit="10" />
        </div>

        <!-- Active Trades -->
        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Active Trades</flux:heading>

                <flux:button
                    href="{{ route('trades.active') }}"
                    wire:navigate="true"
                    size="sm"
                >
                    View All
                </flux:button>
            </div>

            <livewire:trades.active-trades limit="4" />
        </div>

        <!-- Latest Trade Orders -->
        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Latest Trade Orders</flux:heading>

                <flux:button
                    href="{{ route('trades.history') }}"
                    wire:navigate="true"
                    size="sm"
                >
                    View All
                </flux:button>
            </div>

            <livewire:trades.latest-orders limit="4" />
        </div>
    </div>
</x-layouts.app>
