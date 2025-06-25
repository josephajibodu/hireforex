<x-layouts.app>
    @php
        $withdrawalSettings = app(\App\Settings\WithdrawalSetting::class);
    @endphp

    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Withdraw Funds</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Withdraw funds to your USDT wallet address or Bybit UID.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">
                Withdrawal Instructions:
            </flux:heading>
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>Enter the amount you want to withdraw (Min: {{ $withdrawalSettings->minimum_withdrawal_amount }} USDT, Max: {{ $withdrawalSettings->maximum_withdrawal_amount }} USDT).</li>
                <li>You can withdraw by providing your USDT Address or Bybit UID</li>
                <li>All withdrawals will be completed within {{ $withdrawalSettings->withdrawal_processing_time_hours }} hour(s).</li>
                <li>Please note that Cardbeta charges {{ $withdrawalSettings->withdrawal_fee_percentage }}% of the total amount withdrawn as transaction fee.</li>
            </ol>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:withdrawal-page />
            </div>

            <div class="md:border rounded-lg py-4 col-span-full sm:col-span-7">
                <div class="mb-8 flex justify-between md:px-4">
                    <flux:heading class="text-xl! md:text-2xl!">Recent Withdrawals</flux:heading>
                    <flux:button
                        href="{{ route('withdrawal.history') }}"
                        size="sm"
                    >
                        View All
                    </flux:button>
                </div>
                <livewire:withdrawals.list-withdrawals :limit="5" />
            </div>
        </div>
    </div>
</x-layouts.app>
