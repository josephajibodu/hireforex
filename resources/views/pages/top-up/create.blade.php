<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Top Up Balance</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Fund your Cardbeta wallet using Bybit or USDT transfer.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">
                Top Up Your Cardbeta Balance
            </flux:heading>
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>You can fund your Cardbeta wallet using Bybit UID transfer or USDT transfer (TRC-20 or BEP-20).</li>
                <li>After making the transfer, submit your details and screenshot for confirmation.</li>
                <li>Your Cardbeta wallet will be credited within 10 minutes after confirmation.</li>
                <li>If payment is not received or invalid, your top-up request will be rejected and you will be notified.</li>
            </ol>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:top-up.create-form type="{{ $type }}" />
            </div>

            <div class="md:border rounded-lg py-4 col-span-full sm:col-span-7">
                <div class="mb-8 flex justify-between md:px-4">
                    <flux:heading class="text-xl! md:text-2xl!">Recent Top-Ups</flux:heading>
                </div>
                <livewire:top-up.history />
            </div>
        </div>
    </div>
</x-layouts.app>
