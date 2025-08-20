<?php

use Livewire\Volt\Component;
use App\Models\Trader;

new class extends Component {
    public $limit = 10;
    public $showHeader = true;
    public $guest = false;

    public function mount($limit = 10, $showHeader = true, $guest = false)
    {
        $this->limit = $limit;
        $this->showHeader = $showHeader;
        $this->guest = $guest;
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
    @if($this->showHeader)
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Available Traders</h2>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Choose from our selection of verified professional forex traders
            </p>
        </div>
    @endif

    @if($this->traders->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($this->traders as $trader)
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-brand-600">{{ $trader->name }}</h3>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600">{{ $trader->potential_return }}%</div>
                            <!-- Text updated in the line below -->
                            <div class="text-sm text-gray-500">Returns (capital included)</div>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">MBG:</span>
                            <span class="font-medium text-green-600">{{ $trader->mbg_rate }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Track:</span>
                            <div class="flex gap-1">
                                @foreach(str_split($trader->track_record) as $result)
                                    <span class="w-3 h-3 rounded-full text-xs flex items-center justify-center font-bold {{ $result === 'W' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        {{ $result }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Experience:</span>
                            <span class="font-medium">{{ $trader->experience_years }} years</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Min Capital:</span>
                            <span class="font-medium">${{ number_format($trader->min_capital, 0) }}</span>
                        </div>
                    </div>
                    @if($this->guest)
                        <flux:button href="{{ route('register') }}" variant="primary" class="w-full">
                            Hire Now
                        </flux:button>
                    @else
                        <flux:button
                            href="{{ route('traders.show', $trader) }}"
                            wire:navigate="true"
                            variant="primary"
                            class="w-full"
                        >
                            Hire Now
                        </flux:button>
                    @endif
                </div>
            @endforeach
        </div>

        @if($this->guest)
            <div class="text-center mt-8">
                <flux:button href="{{ route('register') }}" variant="outline">
                    View All Traders
                </flux:button>
            </div>
        @endif
    @else
        <div class="text-center py-8">
            <flux:icon name="chart-candlestick" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <p class="text-gray-600">No traders available at the moment.</p>
        </div>
    @endif
</div>