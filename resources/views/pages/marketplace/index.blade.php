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
                <p>Browse and select any gift card you want Cardbeta to help you buy and resell for profit.</p>

                <p><strong>How it works:</strong> When you purchase a gift card, Cardbeta will buy it at a discounted rate and automatically resell it at the listed resale value. After the delivery period, you'll receive the resale value directly in your Cardbeta balance.</p>

                <p><strong>Review the Details:</strong> Before proceeding, carefully review the gift card's cost, resale value, and delivery time. The resale value is the amount that will be credited to your Cardbeta balance once the delivery period is complete.</p>

                <p><strong>Automatic Processing:</strong> Once you click "Buy now", Cardbeta manages the entire transaction for you. Cardbeta will purchase the discounted gift card and then resell it at the listed price, completing the process within the specified delivery time.</p>

                <p><strong>Profit Generation:</strong> You'll be able to track your order progress and receive the resale value automatically credited to your balance when the delivery time elapses.</p>

                <p class="font-medium">Buying and selling of gift cards are subject to availability on a first-come, first-served basis. If a card is unavailable, please select another or check back later.</p>
            </div>
        </div>

        <livewire:giftcards.available-giftcards />
    </div>
</x-layouts.app>
