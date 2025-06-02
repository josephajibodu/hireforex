<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Withdraw USDT</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Withdraw via USDT directly from profitchain
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>As a profitchain member, you can use your withdraw from your Withdrawal balance Via USDT.</li>
                <li>Make sure you have up to {{ $min_amount }} USD in your withdrawal balance.</li>
                <li>Input Your USDT address below</li>
                <li>Select your Network</li>
                <li>And click on 'Proceed'.</li>
                <li>Please make sure you cross-check your Wallet address before proceeding.</li>
                <li>Transaction fee is 4%</li>
            </ol>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:buy-usdt.create-form />
            </div>

            <div class="md:border rounded-lg py-4 col-span-full sm:col-span-7">
                <div class="mb-4 flex justify-between md:px-4">
                    <flux:heading class="text-xl! md:text-2xl!">History</flux:heading>

                    <flux:button
                        href="{{ route('buy-usdt.index') }}"
                        size="sm"
                    >
                        View All
                    </flux:button>
                </div>

                <livewire:buy-usdt.list-usdt-orders :limit="5" />
            </div>
        </div>

    </div>
</x-layouts.app>