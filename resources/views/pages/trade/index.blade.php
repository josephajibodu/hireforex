<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Arbitrage Market</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Welcome to the profitchain arbitrage market.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>Select any arbitrage currency cobination you wish to trade.</li>
                <li>Cross-check the trade time and expected profit before proceeding.</li>
                <li>Once you select and click "TRADE", the trade details will appear under "MY ACTIVE ARBITRAGE"</li>
                <li>Arbitrage market operates on a first-come, first-served basis. If a trade
                    combination reaches its capacity, please choose another trade combination.</li>
                    <li><b>Profitchain does not control or influence the market in any way. We do not determine rates or the duration it takes for trades to complete.</b></li>
                    <li><b>Arbitrage Market ID: 4573xxx (Renewed on: 14/02/2025) .</b></li>
            </ol>
        </div>

        <livewire:trade.available-trades />

    </div>
</x-layouts.app>