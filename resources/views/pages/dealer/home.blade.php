<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Welcome to your dealer dashboard, {{ ucfirst(auth()->user()->username) }}</flux:heading>

        <flux:subheading size="lg" class="mb-6">Here's what's new today</flux:subheading>

        <flux:separator variant="subtle" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
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

            <!-- Withdrawal Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Trading Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($user->trading_balance, 1, "$") }}</h2>
                    <p class="text-xs text-gray-400">Reserved for Sale</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="chart-candlestick" />
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
