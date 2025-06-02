<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component {

    public int $limit = 0;

    public function mount(int $limit = 0)
    {
        $this->limit = $limit;
    }

}; ?>

<div class="">
    <div class="mb-8 flex justify-between px-4">
        <flux:heading size="lg"></flux:heading>
    </div>

    <x-table class="bg-gray-50 rounded-none! border-0 hidden md:table">
        <x-table.columns>
            <x-table.column >Reference</x-table.column>
            <x-table.column >Combination</x-table.column>
            <x-table.column>Amount</x-table.column>
            <x-table.column>Expected Returns</x-table.column>
            <x-table.column class="text-center">Time</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column>Date</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $tradesQuery = Auth::user()->trades()->scopes('active')->latest();
                $trades = $limit ? $tradesQuery->take(5)->get() : $tradesQuery->paginate();
            @endphp

            @forelse($trades as $trade)
                <x-table.row class="cursor-pointer">
                    <x-table.cell>{{ $trade->reference}}</x-table.cell>
                    <x-table.cell>{{ $trade->currency_pair_name}}</x-table.cell>
                    <x-table.cell>{{ to_money($trade->capital, 100, '$') }}</x-table.cell>
                    <x-table.cell>{{ to_money($trade->total_roi, 100, '$') }}</x-table.cell>
                    <x-table.cell>
                        <x-countdown-timer :time-remaining="$trade->getTimeLeft()" message="Trade Completed" />
                    </x-table.cell>
                    <x-table.cell>
                        <flux:badge :color="$trade->status->getFluxColor()" >{{ $trade->status->getLabel() }}</flux:badge>
                    </x-table.cell>
                    <x-table.cell>{{ $trade->created_at->format('d M h:i A')}}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="7" class="text-center">No active trade yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    <div class="md:hidden">
        @forelse($trades as $trade)
            <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Reference:</flux:heading>
                    <flux:subheading class="text-end!">{{ $trade->reference }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Combination:</flux:heading>
                    <flux:subheading class="text-end!">{{ $trade->currency_pair_name }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount:</flux:heading>
                    <flux:subheading>{{ to_money($trade->capital, 100, '$') }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Expected Returns:</flux:heading>
                    <flux:subheading>{{ to_money($trade->total_roi, 100, '$') }}</flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Time:</flux:heading>
                    <flux:subheading>
                        <x-countdown-timer :time-remaining="$trade->getTimeLeft()" message="Completed" />
                    </flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Status:</flux:heading>
                    <flux:badge :color="$trade->status->getFluxColor()">{{ $trade->status->getLabel() }}</flux:badge>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Date:</flux:heading>
                    <flux:subheading>{{ $trade->created_at->format('d M h:i A') }}</flux:subheading>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No active trade yet!</div>
        @endforelse
    </div>

    @if(!$limit)
        <div class="my-2">
            {{ $trades->links() }}
        </div>
    @endif
</div>
