<x-layouts.app>
    @section('title', 'Personal Dashboard')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Hello, {{ ucfirst(auth()->user()->username) }}</flux:heading>

        <flux:subheading size="lg" class="mb-6">Glad to have you here today!</flux:subheading>

        <flux:separator variant="subtle" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <!-- USD Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Main Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($user->main_balance, 1, "$") }}</h2>
                    <p class="text-xs text-gray-400">Total available balance</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="wallet" />
                    </div>
                </div>
            </div>

            <!-- Bonus Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Bonus Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($user->bonus_balance, 1, "$") }}</h2>
                    <p class="text-xs text-gray-400">Total available bonus</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="hand-coins" />
                    </div>
                </div>
            </div>

            <!-- Reserve Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Reserve Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($user->reserve_balance, 1, "$") }}</h2>
                    <p class="text-xs text-gray-400">Pending USD Funds</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="database-backup" />
                    </div>
                </div>
            </div>

            <!-- Withdrawal Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Withdrawal Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($user->withdrawal_balance, 1, '$') }}</h2>
                    <p class="text-xs text-gray-400">Available for withdrawal</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="piggy-bank" />
                    </div>
                </div>
            </div>
        </div>

        <livewire:kyc-form />

        <livewire:trade-embargo />

        @include('pages.partials.quicklinks')

        @include('pages.partials.additionallinks')

        <div class="mt-12 space-y-4">
            <flux:heading class="text-xl! md:text-2xl!">Join Fellow Profitchain Members</flux:heading>
            <div class="flex flex-col sm:flex-row  gap-4">
                <flux:button variant="primary" href="https://profitchain.com/community" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                    Join WhatsApp Group
                </flux:button>
                <flux:button href="https://profitchain.com/community" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                    Join WhatsApp Group
                </flux:button>
            </div>
        </div>

        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Active Trades</flux:heading>

                <flux:button
                    href="{{ route('trade-arbitrage.active_trades') }}"
                    wire:navigate="true"
                    size="sm"
                >
                    View All
                </flux:button>
            </div>

            <livewire:trade.list-trades />
        </div>

        @include('pages.partials.dealership-partnership-links')

        <livewire:dashboard.bank-details />
    </div>

</x-layouts.app>
