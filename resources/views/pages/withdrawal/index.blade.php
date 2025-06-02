<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Withdrawal History</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Withdraw funds directly to your bank account.
            </flux:subheading>
        </div>

        <div class="md:border rounded-lg py-4 col-span-7">
            <div class="mb-4 flex justify-between">
                <flux:heading size="lg" class="hidden md:block md:ps-4 text-xl! md:text-2xl!">History</flux:heading>
            </div>

            <livewire:withdrawals.list-withdrawals />
        </div>

    </div>
</x-layouts.app>