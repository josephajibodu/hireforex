<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Gift Card Marketplace</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Welcome to the Cardbeta gift card marketplace.
            </flux:subheading>
        </div>

        <!-- Welcome Section -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border mb-8">
            <flux:heading class="text-2xl! mb-4">Welcome to the Cardbeta Gift Card Marketplace!</flux:heading>
            <div class="space-y-4 text-gray-700 dark:text-gray-300">
                <p>Browse and select any gift card you will like to purchase.</p>
                <p>Before proceeding, review the delivery time and cost details carefully.</p>
                <p>Once you click "BUY NOW," your transaction will appear under "CURRENT ORDER."</p>
                <p>You'll be able to track your order, and once the delivery duration has passed, the gift card codes will become visible.</p>
                <p class="font-medium">Please note: Gift card availability is on a first-come, first-served basis. If a specific card is sold out, you can either choose a different one or check back later.</p>
            </div>
        </div>

        <livewire:giftcards.available-giftcards />
    </div>
</x-layouts.app>
