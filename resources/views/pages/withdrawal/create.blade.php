<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Withdraw Funds</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Withdraw funds directly to your bank account.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">
                The Naira withdrawal process is simple and fast. Just follow the steps below to withdraw
                from your Withdrawal Balance:
            </flux:heading>
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>Enter the amount you want to withdraw.</li>
                <li>Withdrawals are processed using the standard USD rate, which you can check
                    below.</li>
                <li>All withdrawals will be completed within 24 hours.</li>
                <li>If your bank details are incorrect, you can update them on your dashboard.</li>
                <li>Please note that Profitchain charge {{ $rate * 100 }}% of the total amount withdrawn as
                    transaction fee.</li>
                    <li><b>This withdrawal option is only for Naira Withdrawal, to withdraw via USDT, use the Withdraw USDT(Tether) Option.</b></li>
            </ol>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:withdrawals.create-form />
            </div>

            <div class="md:border rounded-lg py-4 col-span-full sm:col-span-7">
                <div class="mb-8 flex justify-between md:px-4">
                    <flux:heading class="text-xl! md:text-2xl!">History</flux:heading>

                    <flux:button
                        href="{{ route('withdrawal.index') }}"
                        size="sm"
                    >
                        View All
                    </flux:button>
                </div>

                <livewire:withdrawals.list-withdrawals :limit="4" />
            </div>
        </div>

    </div>
</x-layouts.app>