<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component {

    public int $limit = 0;

    #[On('transfer-created')]
    public function mount(int $limit = 0)
    {
        $this->limit = $limit;
    }

}; ?>

<div class="">
    <div class="mb-8 flex justify-between px-4">
        <flux:heading size="lg">History</flux:heading>

        @if($limit)
            <flux:button
                    href="{{ route('transfer.index') }}"
                    size="sm"
            >
                View All
            </flux:button>
        @endif
    </div>

    <x-table class="bg-gray-50 rounded-none! border-0">
        <x-table.columns>
            <x-table.column >Recipient</x-table.column>
            <x-table.column>Amount(USD)</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column class="text-center">Date</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $transferQuery = Auth::user()->transfers()->latest();
                $transfers = $limit ? $transferQuery->take(3)->get() : $transferQuery->paginate();
            @endphp

            @forelse($transfers as $transfer)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">{{ $transfer->recipient->username }}</x-table.cell>
                    <x-table.cell class="">
                        <flux:heading>{{ to_money($transfer->amount, 100, '$') }}</flux:heading>
                    </x-table.cell>
                    <x-table.cell>
                        <x-flux::badge color="{{ $transfer->status->getFluxColor() }}">
                            {{ $transfer->status->getLabel() }}
                        </x-flux::badge>
                    </x-table.cell>
                    <x-table.cell class="text-center" >
                        {{ $transfer->created_at->format('d M h:i A') }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="4" class="text-center">No transfers yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    @if(!$limit)
        <div class="my-2">
            {{ $transfers->links() }}
        </div>
    @endif
</div>
