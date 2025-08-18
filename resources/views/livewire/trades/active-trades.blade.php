<?php

use Livewire\Volt\Component;

new class extends Component {
    public $limit = 4;

    public function mount($limit = 4)
    {
        $this->limit = $limit;
    }

    public function getActiveTradesProperty()
    {
        return auth()->user()->activeTrades()->with('trader')->take($this->limit)->get();
    }
}; ?>

<div>
    @if($this->activeTrades->count() > 0)
        <div class="grid gap-4">
            @foreach($this->activeTrades as $trade)
                <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-brand-100 dark:bg-brand-900/20 rounded-full flex items-center justify-center">
                            <flux:icon name="chart-candlestick" class="w-4 h-4 text-brand-600 dark:text-brand-400" />
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
                        <div class="text-sm font-medium text-neutral-900 dark:text-white">
                            {{ $trade->mbg_rate }}% MBG
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">
                            @if($trade->getTimeRemainingAttribute())
                                @php
                                    $timeLeft = $trade->getTimeRemainingAttribute();
                                    $days = floor($timeLeft / 86400);
                                    $hours = floor(($timeLeft % 86400) / 3600);
                                    $minutes = floor(($timeLeft % 3600) / 60);
                                    $seconds = $timeLeft % 60;

                                    if ($days > 0) {
                                        echo $days . 'd ' . str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
                                    } else {
                                        echo str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
                                    }
                                @endphp left
                            @else
                                0:00:00 left
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <flux:icon name="chart-candlestick" class="w-8 h-8 text-neutral-400 mx-auto mb-2" />
            <p class="text-sm text-neutral-600 dark:text-neutral-400">No active trades</p>
        </div>
    @endif
</div>
