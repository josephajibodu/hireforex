<?php

use Livewire\Volt\Component;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use App\Models\Order;

new class extends Component {
};

?>

<div class="mt-12">
    <flux:heading class="text-xl! md:text-2xl! mb-4">Latest Giftcard Orders</flux:heading>

    <x-table class="bg-gray-50 shadow-lg rounded-none">
        <x-table.columns>
            <x-table.column>Username</x-table.column>
            <x-table.column>Card Type</x-table.column>
            <x-table.column>Resell Value</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $orders = Order::query()->latest()->where('status', \App\Enums\OrderStatus::Completed)->take(4)->get();
            @endphp

            @forelse($orders as $order)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">{{ obfuscate($order->user->username) }}</x-table.cell>
                    <x-table.cell class="">
                        <flux:heading>{{ $order->giftcard->name }}</flux:heading>
                    </x-table.cell>
                    <x-table.cell class="min-w-24 capitalize">
                        <flux:heading>{{ to_money($order->giftcard->resell_value, 1, '$') }}</flux:heading>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="8" class="text-center">List not updated yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

</div>
