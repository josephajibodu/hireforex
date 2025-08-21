<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Transfer USDT</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Send USDT to other users on HireForex.
            </flux:subheading>
        </div>

        <!-- Transfer Info -->
        <div class="bg-gray-50 dark:bg-neutral-800 p-6 rounded-lg border border-neutral-200 dark:border-neutral-700">
            <p class="text-sm text-neutral-700 dark:text-neutral-300 mb-3">
                Easily transfer funds between HireForex users in just a few clicks. With the USDT Transfer Feature, you can securely send Tether (USDT) directly to another user’s HireForex balance. This feature is designed for traders, clients, and partners who want a quick and reliable way to share funds within the HireForex ecosystem without leaving the platform.
            </p>

            <flux:heading size="lg" class="mb-2 text-gray-800 dark:text-gray-100">
                How It Works
            </flux:heading>

            <ol class="list-decimal list-inside text-sm text-neutral-700 dark:text-neutral-300 space-y-1">
                <li>Enter the recipient’s username (make sure it is correct to avoid errors).</li>
                <li>Specify the amount of USDT you want to transfer.</li>
                <li>Add a short note or reference for the transfer (e.g., “profit share,” “refund,” or “gift”).</li>
                <li>Confirm your details and submit the transfer.</li>
                <li>The recipient’s HireForex balance will be updated instantly once the transaction is processed.</li>
            </ol>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:transfers.create-form />
            </div>

            <div class="border rounded-lg py-4 col-span-full sm:col-span-7">
                <livewire:transfers.list-transfers :limit="3" />
            </div>
        </div>

    </div>
</x-layouts.app>
