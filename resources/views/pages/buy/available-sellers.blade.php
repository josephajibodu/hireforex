<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Buy USD</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Securely purchase USD from verified dealers in just a few steps.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">How It Works</flux:heading>
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                 <li>You can purchase USD using either USDT or the Naira payment method through a dealer.</li>
                <li>Select a dealer below and click to proceed.</li>
                <li>Enter the amount of USD you wish to buy and view the Naira or USDT equivalent.</li>
                <li>Confirm your entry and proceed to payment.</li>
                <li>Upload your payment proof and notify the dealer.</li>
                <li>The dealer will confirm your payment within 1 hour.</li>
                <li>For verification, you can contact the dealer via WhatsApp or call.</li>
                <li>If an issue arises, use the **Dispute** feature for quick resolution.</li>
                <li><b>Always check the PAYMENT TYPE (Bank Transfer or USDT) accepted by the dealer before you continue</b></li>
                <li><b>If USD is Out Of Stock, check back tomorrow morning from 8 AM.</b></li>
                <li><b>Users who engage in dishonesty or refuse to complete payment will be permanently banned. Please do not place an order unless you intend to pay.</b></li>
            </ol>
        </div>

        <livewire:order.buy-usd-p2p />

    </div>
</x-layouts.app>