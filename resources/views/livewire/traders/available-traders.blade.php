<?php

use Livewire\Volt\Component;
use App\Models\Trader;

new class extends Component {
    public $limit = 10;

    public function mount($limit = 10)
    {
        $this->limit = $limit;
    }

    public function getTradersProperty()
    {
        return Trader::where('is_available', true)
            ->orderBy('mbg_rate', 'desc')
            ->orderBy('experience_years', 'desc')
            ->take($this->limit)
            ->get();
    }
}; ?>

<div>
    @if($this->traders->count() > 0)
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($this->traders as $trader)
                <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-4 shadow-sm">
                    <!-- Trader Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-brand-600 dark:text-brand-400">{{ $trader->name }}</h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $trader->experience_years }} years</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-bold text-green-600 dark:text-green-400">{{ $trader->potential_return }}%</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-400">Returns</div>
                        </div>
                    </div>

                    <!-- Trader Details -->
                    <div class="space-y-2 mb-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">MBG:</span>
                            <span class="font-medium text-green-600 dark:text-green-400">{{ $trader->mbg_rate }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Track:</span>
                            <div class="flex gap-1">
                                @foreach(str_split($trader->track_record) as $result)
                                    <span class="w-3 h-3 rounded-full text-xs flex items-center justify-center font-bold {{ $result === 'W' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        {{ $result }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Pair:</span>
                            <span class="font-medium">{{ $trader->favorite_pairs }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Min:</span>
                            <span class="font-medium">${{ number_format($trader->min_capital, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Duration:</span>
                            <span class="font-medium">{{ $trader->duration_days }}d</span>
                        </div>
                    </div>

                    <!-- Hire Button -->
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
    @else
        <div class="text-center py-8">
            <flux:icon name="chart-candlestick" class="w-12 h-12 text-neutral-400 mx-auto mb-4" />
            <p class="text-neutral-600 dark:text-neutral-400">No traders available at the moment.</p>
        </div>
    @endif
</div>
