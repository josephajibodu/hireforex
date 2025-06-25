<x-layouts.app>
    <div class="max-w-screen-xl mx-auto py-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Withdrawal History</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                View all your USDT withdrawal requests and their status.
            </flux:subheading>
        </div>

        <div class="bg-white dark:bg-neutral-900 rounded-lg border">
            <div class="p-6">
                <livewire:withdrawals.list-withdrawals />
            </div>
        </div>
    </div>
</x-layouts.app>
