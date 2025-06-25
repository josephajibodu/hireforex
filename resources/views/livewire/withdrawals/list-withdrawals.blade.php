<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component {

    public int $limit = 0;

    #[On('withdrawal-initiated')]
    public function mount(int $limit = 0)
    {
        $this->limit = $limit;
    }

}; ?>

<div class="overflow-x-auto">
    <x-table class="bg-gray-50 rounded-none! border-0 hidden md:table">
        <x-table.columns>
            <x-table.column>Reference</x-table.column>
            <x-table.column class="text-center">Amount (USDT)</x-table.column>
            <x-table.column class="text-center">Amount Payable (USDT)</x-table.column>
            <x-table.column>Withdrawal Method</x-table.column>
            <x-table.column>Destination</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column class="text-center">Date</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $withdrawalQuery = Auth::user()->withdrawals()->latest();
                $withdrawals = $limit ? $withdrawalQuery->take($limit)->get() : $withdrawalQuery->paginate();
            @endphp

            @forelse($withdrawals as $withdrawal)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">{{ $withdrawal->reference }}</x-table.cell>
                    <x-table.cell class="">
                        <div class="text-center">
                            <flux:heading>{{ number_format($withdrawal->amount, 2) }} USDT</flux:heading>
                        </div>
                    </x-table.cell>
                    <x-table.cell class="">
                        <div class="text-center">
                            <flux:heading>{{ number_format($withdrawal->amount_payable, 2) }} USDT</flux:heading>
                        </div>
                    </x-table.cell>
                    <x-table.cell class="">
                        <flux:heading>{{ $withdrawal->method_label }}</flux:heading>
                        @if($withdrawal->network_type)
                            <flux:subheading>{{ $withdrawal->network_type }}</flux:subheading>
                        @endif
                    </x-table.cell>
                    <x-table.cell class="">
                        <flux:heading class="break-words">{{ $withdrawal->destination }}</flux:heading>
                    </x-table.cell>
                    <x-table.cell class="text-center">
                        <x-flux::badge color="{{ $withdrawal->status->getFluxColor() }}">
                            {{ $withdrawal->status->getLabel() }}
                        </x-flux::badge>
                    </x-table.cell>
                    <x-table.cell class="text-center" >
                        {{ $withdrawal->created_at->format('d M h:i A') }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="7" class="text-center">No withdrawals yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    <div class="md:hidden">
        @forelse($withdrawals as $withdrawal)
            <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Reference:</flux:heading>
                    <flux:subheading class="uppercase text-end">{{ $withdrawal->reference }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount (USDT):</flux:heading>
                    <flux:subheading>{{ number_format($withdrawal->amount, 2) }} USDT</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount Payable (USDT):</flux:heading>
                    <flux:subheading>{{ number_format($withdrawal->amount_payable, 2) }} USDT</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Method:</flux:heading>
                    <div class="text-right">
                        <flux:subheading>{{ $withdrawal->method_label }}</flux:subheading>
                        @if($withdrawal->network_type)
                            <flux:subheading class="text-gray-500">{{ $withdrawal->network_type }}</flux:subheading>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Destination:</flux:heading>
                    <flux:subheading class="break-words text-right">{{ $withdrawal->destination }}</flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Status:</flux:heading>
                    <flux:badge color="{{ $withdrawal->status->getFluxColor() }}">
                        {{ $withdrawal->status->getLabel() }}
                    </flux:badge>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Date:</flux:heading>
                    <flux:subheading>{{ $withdrawal->created_at->format('d M h:i A') }}</flux:subheading>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No withdrawals yet!</div>
        @endforelse
    </div>

    @if(!$limit)
        <div class="my-2">
            {{ $withdrawals->links() }}
        </div>
    @endif
</div>
