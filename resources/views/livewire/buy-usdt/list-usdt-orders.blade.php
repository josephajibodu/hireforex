<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component {

    public int $limit = 0;

    #[On('usdt-withdrawal-initiated')]
    public function mount(int $limit = 0)
    {
        $this->limit = $limit;
    }

}; ?>

<div class="w-full overflow-x-auto">
    <x-table class="bg-gray-50 rounded-none! border-0 hidden md:table">
        <x-table.columns>
            <x-table.column >Reference</x-table.column>
            <x-table.column class="text-center">Amount(USD)</x-table.column>
            <x-table.column class="text-center">You Get(USDT)</x-table.column>
            <x-table.column>Network</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column class="text-center">Date</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $cryptoTradeQuery = Auth::user()->cryptoTrades()->latest();
                $cryptoTrades = $limit ? $cryptoTradeQuery->take($limit)->get() : $cryptoTradeQuery->paginate();
            @endphp

            @forelse($cryptoTrades as $cryptoTrade)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">{{ $cryptoTrade->reference }}</x-table.cell>
                    <x-table.cell class="">
                        <div class="text-center">
                            <flux:heading>{{ to_money($cryptoTrade->amount, 100, '$') }}</flux:heading>
                        </div>
                    </x-table.cell>
                    <x-table.cell class="">
                        <div class="text-center">
                            <flux:heading>{{ to_money($cryptoTrade->amount_sent, 100, '$') }}</flux:heading>
                        </div>
                    </x-table.cell>
                    <x-table.cell class="">
                        <flux:heading>{{ $cryptoTrade->network }}</flux:heading>
                        <flux:subheading>{{ $cryptoTrade->wallet_address }}</flux:subheading>
                    </x-table.cell>
                    <x-table.cell class="text-center">
                        <x-flux::badge color="{{ $cryptoTrade->status->getFluxColor() }}">
                            {{ $cryptoTrade->status->getLabel() }}
                        </x-flux::badge>
                    </x-table.cell>
                    <x-table.cell class="text-center" >
                        {{ $cryptoTrade->created_at->format('d M h:i A') }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="6" class="text-center">No withdrawals yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    <div class="md:hidden">
        @forelse($cryptoTrades as $cryptoTrade)
            <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Reference:</flux:heading>
                    <flux:subheading class="uppercase text-end">{{ $cryptoTrade->reference }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount (USD):</flux:heading>
                    <flux:subheading>{{ to_money($cryptoTrade->amount, 100, '$') }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">You Get (USDT):</flux:heading>
                    <flux:subheading>{{ to_money($cryptoTrade->amount_sent, 100, '$') }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Network:</flux:heading>
                    <flux:subheading>{{ $cryptoTrade->network }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Wallet Address:</flux:heading>
                    <flux:subheading class="truncate max-w-[150px]">{{ $cryptoTrade->wallet_address }}</flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Status:</flux:heading>
                    <flux:badge color="{{ $cryptoTrade->status->getFluxColor() }}">
                        {{ $cryptoTrade->status->getLabel() }}
                    </flux:badge>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Date:</flux:heading>
                    <flux:subheading>{{ $cryptoTrade->created_at->format('d M h:i A') }}</flux:subheading>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No crypto trades yet!</div>
        @endforelse
    </div>

    @if(!$limit)
        <div class="my-2">
            {{ $cryptoTrades->links() }}
        </div>
    @endif
</div>
