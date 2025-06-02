<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Create Sell Order</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Securely purchase USD from verified dealers in just a few steps.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">To sell USD, follow the steps below:</flux:heading>
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>Click on <b>CREATE SELL ORDER</b></li>
                <li>Specify the amount you want to offer for sale.</li>
                <li>Confirm your entry and proceed to payment.</li>
                <li>Be sure to set your selling terms and conditions as well.</li>
                <li>Once all your USD are sold, update your status to SOLD OUT.</li>
                <li>If you stop selling, click <b>UNPUBLISH SELL ADVERT</b> to remove your listing from the buy
                    page. You can republish it later when you have more USD to sell.</li>
                <li>After receiving payment, click Confirm Payment to release the USD.</li>
            </ol>
        </div>

        <div class="mt-8">
            <livewire:order.create-sell-advert />
        </div>
    </div>
</x-layouts.app>