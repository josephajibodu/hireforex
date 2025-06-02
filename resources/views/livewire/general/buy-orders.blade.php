<?php

use Livewire\Volt\Component;
use App\Models\SellAdvert;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateSellAdvert;
use Livewire\Attributes\Validate;
use App\Models\Order;

new class extends Component {
};

?>

<div class="mt-12">
    <flux:heading class="text-xl! md:text-3xl text-center w-full mb-4">Latest Buy Order Transactions</flux:heading>

    <x-table class="bg-gray-50 shadow-lg rounded-none">
        <x-table.columns>
            <x-table.column>Buyer Username</x-table.column>
            <x-table.column>Amount(USD)</x-table.column>
            <x-table.column>Dealer Username</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $orders = Order::query()->with(['buyer', 'sellAdvert.user'])->latest()->take(15)->get();
            @endphp
            @forelse($orders as $order)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">{{ obfuscate($order->buyer->username) }}</x-table.cell>
                    <x-table.cell class="">
                        <flux:heading>{{ to_money($order->coin_amount, 100, '$') }}</flux:heading>
                    </x-table.cell>
                    <x-table.cell class="min-w-24 capitalize">{{ obfuscate($order->sellAdvert->user->username) }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="8" class="text-center">List not updated yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

</div>
