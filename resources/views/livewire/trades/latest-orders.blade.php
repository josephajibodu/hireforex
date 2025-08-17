<?php

use Livewire\Volt\Component;

new class extends Component {
    public $limit = 4;

    public function mount($limit = 4)
    {
        $this->limit = $limit;
    }

    public function getLatestTradesProperty()
    {
        return auth()->user()->trades()->with('trader')->latest()->take($this->limit)->get();
    }
}; ?>

<div>
    @if($this->latestTrades->count() > 0)
        <div class="grid gap-4">
            @foreach($this->latestTrades as $trade)
                <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 {{ $trade->isCompleted() ? 'bg-green-100 dark:bg-green-900/20' : ($trade->isRefunded() ? 'bg-blue-100 dark:bg-blue-900/20' : 'bg-brand-100 dark:bg-brand-900/20') }} rounded-full flex items-center justify-center">
                            @if($trade->isCompleted())
                                <flux:icon name="check-circle" class="w-4 h-4 text-green-600 dark:text-green-400" />
                            @elseif($trade->isRefunded())
                                <flux:icon name="refresh-cw" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                            @else
                                <flux:icon name="chart-candlestick" class="w-4 h-4 text-brand-600 dark:text-brand-400" />
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">
                                {{ $trade->trader->name }}
                            </p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                ${{ number_format($trade->amount, 2) }} • {{ $trade->potential_return }}% return
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium {{ $trade->isCompleted() ? 'text-green-600 dark:text-green-400' : ($trade->isRefunded() ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-900 dark:text-white') }}">
                            {{ $trade->mbg_rate }}% MBG
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">
                            @if($trade->isCompleted())
                                Completed
                            @elseif($trade->isRefunded())
                                Refunded
                            @else
                                Active
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <flux:icon name="file-clock" class="w-8 h-8 text-neutral-400 mx-auto mb-2" />
            <p class="text-sm text-neutral-600 dark:text-neutral-400">No trade orders yet</p>
        </div>
    @endif
</div>
