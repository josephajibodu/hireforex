<?php

use Livewire\Volt\Component;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public int $limit = 10;

    public function with(): array
    {
        return [
            'transfers' => Transfer::query()
                ->where(function ($q) {
                    $q->where('user_id', Auth::id())
                      ->orWhere('recipient_id', Auth::id());
                })
                ->latest()
                ->take($this->limit)
                ->get(),
        ];
    }
}; ?>

<div>
    <div class="px-4">
        <h3 class="text-lg font-semibold mb-3">Recent Transfers</h3>
    </div>
    <div class="divide-y">
        @forelse($transfers as $transfer)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="text-sm text-neutral-600">Ref: {{ $transfer->reference ?? '-' }}</div>
                    <div class="text-xs text-neutral-500 max-w-md">{{ $transfer->notes ?? '-' }}</div>
                    <div class="text-xs text-neutral-500 mt-1">
                        <span>From: {{ $transfer->user->username }}</span>
                        <span class="mx-2">→</span>
                        <span>To: {{ $transfer->recipient->username }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">${{ number_format($transfer->amount / 100, 2) }}</div>
                    <flux:badge color="{{ $transfer->status->getFluxColor() }}">{{ $transfer->status->getLabel() }}</flux:badge>
                    <div class="text-xs text-neutral-500 mt-1">{{ $transfer->created_at->diffForHumans() }}</div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-neutral-500">No transfers yet.</div>
        @endforelse
    </div>
</div>


