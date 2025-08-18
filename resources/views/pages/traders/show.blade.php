<x-layouts.app>
    @section('title', 'Hire ' . $trader->name)

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4 mb-6">
            <flux:button
                href="{{ route('traders.index') }}"
                wire:navigate="true"
                variant="outline"
            >
                <flux:icon name="arrow-left" class="w-4 h-4 mr-2" />
                Back to Traders
            </flux:button>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Trader Information -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-6">
                    <flux:heading size="xl" level="1" class="text-brand-600 dark:text-brand-400 mb-2">{{ $trader->name }}</flux:heading>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 mb-4">{{ $trader->experience_years }} years of forex trading experience</p>

                    <!-- Key Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $trader->potential_return }}%</div>
                            <div class="text-sm text-green-700 dark:text-green-300">Potential Return</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $trader->mbg_rate }}%</div>
                            <div class="text-sm text-blue-700 dark:text-blue-300">Money Back Guarantee</div>
                        </div>
                    </div>

                    <!-- Track Record -->
                    <div class="mb-6">
                        <flux:heading size="lg" class="mb-3">Track Record (Last 5 Trades)</flux:heading>
                        <div class="flex gap-2">
                            @foreach(str_split($trader->track_record) as $result)
                                <span class="w-8 h-8 rounded-full text-sm flex items-center justify-center font-bold {{ $result === 'W' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $result }}
                                </span>
                            @endforeach
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-2">
                            Win Rate: {{ number_format($trader->win_rate, 1) }}%
                        </p>
                    </div>

                    <!-- Trading Details -->
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Favorite Trading Pair:</span>
                            <span class="font-medium">{{ $trader->favorite_pairs }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Trade Duration:</span>
                            <span class="font-medium">{{ $trader->duration_days }} days</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Minimum Capital:</span>
                            <span class="font-medium">${{ number_format($trader->min_capital, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Available Volume:</span>
                            <span class="font-medium">${{ number_format($trader->available_volume, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hire Trader Form -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-6">
                    <flux:heading size="lg" class="mb-4">Hire {{ $trader->name }}</flux:heading>

                    <livewire:traders.hire-trader-form :trader="$trader" />
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
