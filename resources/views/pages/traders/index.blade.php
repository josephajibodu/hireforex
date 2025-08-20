<x-layouts.app>
    @section('title', 'Available Traders')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Hire Top Traders</flux:heading>

        <flux:subheading size="lg" class="mb-6">Browse through a list of skilled traders and choose the one that fits your objectives.</flux:subheading>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <flux:heading size="lg" class="text-blue-800 dark:text-blue-200 mb-3">How to Choose a Trader on HireForex:</flux:heading>
            <ul class="text-blue-700 dark:text-blue-300 space-y-2 text-sm">
                <li class="flex items-start gap-2">
                    <span class="text-blue-500 font-semibold">•</span>
                    <span><strong>Check Past Performance</strong> – View their last 5 trade results (e.g., WLWWL) to gauge recent success.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500 font-semibold">•</span>
                    <span><strong>Look at MBG %</strong> – Higher MBG (90–100%) offers better protection for your capital.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500 font-semibold">•</span>
                    <span><strong>Review Returns vs Risk</strong> – Higher returns (e.g., 130%) may mean lower MBG—balance risk and reward.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500 font-semibold">•</span>
                    <span><strong>Check Duration</strong> – Pick a trade period (5–14 days) that matches your timeline.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500 font-semibold">•</span>
                    <span><strong>Consider Experience & Pairs</strong> – Traders with 3+ years of experience and stable pairs (e.g., EUR/USD, gold) often manage risk better.</span>
                </li>
            </ul>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($traders as $trader)
                <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <flux:heading size="lg" class="text-brand-600 dark:text-brand-400">{{ $trader->name }}</flux:heading>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $trader->experience_years }} years experience</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $trader->potential_return }}%</div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-400">Returns (capital included)</div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">MBG Rate:</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $trader->mbg_rate }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Track Record:</span>
                            <div class="flex gap-1">
                                @foreach(str_split($trader->track_record) as $result)
                                    <span class="w-4 h-4 rounded-full text-xs flex items-center justify-center font-bold {{ $result === 'W' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        {{ $result }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Favorite Pair:</span>
                            <span class="text-sm font-medium">{{ $trader->favorite_pairs }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Min Capital:</span>
                            <span class="text-sm font-medium">${{ number_format($trader->min_capital, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Available Volume:</span>
                            <span class="text-sm font-medium">${{ number_format($trader->available_volume, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Duration:</span>
                            <span class="text-sm font-medium">{{ $trader->duration_days }} days</span>
                        </div>
                    </div>

                    <flux:button
                        href="{{ route('traders.show', $trader) }}"
                        wire:navigate="true"
                        variant="primary"
                        class="w-full"
                    >
                        Hire Now
                    </flux:button>
                </div>
            @endforeach
        </div>

        @if($traders->hasPages())
            <div class="mt-8">
                {{ $traders->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>