<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use App\Models\Trade;

new class extends Component {

}; ?>

<div class="">
    <div class="mb-8 flex justify-between px-4">
        <flux:heading class="text-xl! md:text-3xl text-center w-full">Latest Arbitrage Market Trade</flux:heading>
    </div>

    <x-table class="bg-gray-50 rounded-none">
        <x-table.columns>
            <x-table.column>Username</x-table.column>
            <x-table.column>Currency Combination</x-table.column>
            <x-table.column>Expected Returns</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $trades = Trade::query()->scopes('active')->with(['user'])->latest()->take(4)->get();
            @endphp

            @forelse($trades as $trade)
                <x-table.row class="cursor-pointer">
                    <x-table.cell>{{ obfuscate($trade->user->username) }}</x-table.cell>
                    <x-table.cell>{{ $trade->currency_pair_name}}</x-table.cell>
                    <x-table.cell>{{ to_money($trade->total_roi, 100, '$') }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="4" class="text-center">List not updated yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

</div>
