<x-layouts.app>
    @section('title', 'Dashboard')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Welcome, {{ ucfirst(auth()->user()->first_name) }}</flux:heading>

        <flux:subheading size="lg" class="mb-6">Your Cardbeta Dashboard</flux:subheading>

        <flux:separator variant="subtle" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <!-- USDT Balance Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">USDT Balance</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($user->main_balance, divider: 1, hideSymbol: true) }} USDT</h2>
                    <p class="text-xs text-gray-400">Available balance for purchases</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="wallet" />
                    </div>
                </div>
            </div>

            <!-- Current Orders Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg flex">
                <div class="flex-1">
                    <h6 class="text-lg font-medium text-gray-800 dark:text-white">Current Orders</h6>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ $user->orders()->where('status', \App\Enums\OrderStatus::Paid)->count() }}</h2>
                    <p class="text-xs text-gray-400">Pending gift card orders</p>
                </div>
                <div>
                    <div class="text-brand-600 bg-brand-100 size-10 flex items-center justify-center rounded">
                        <flux:icon name="shopping-cart" />
                    </div>
                </div>
            </div>
        </div>


        @include('pages.partials.quicklinks')

        <!-- Recent Orders -->
        <div class="relative flex-1 overflow-hidden rounded-xl md:border border-neutral-200 mt-8">
            <div class="md:px-4 pt-4 flex justify-between">
                <flux:heading class="text-xl! md:text-2xl!">Recent Orders</flux:heading>

                <flux:button
                    href="{{ url('/order-history') }}"
                    wire:navigate="true"
                    size="sm"
                >
                    View All
                </flux:button>
            </div>

             <livewire:orders.list-orders />
        </div>

        <!-- Latest Gift Cards -->
        <livewire:giftcards.available-giftcards limit="10" />
    </div>
</x-layouts.app>
