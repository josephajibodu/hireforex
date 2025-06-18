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

    @php
        $ordersQuery = Auth::user()->orders()->latest();
        $orders = $limit ? $ordersQuery->take(5)->get() : $ordersQuery->paginate();
    @endphp

    <x-table class="bg-gray-50 rounded-none! border-0 hidden md:table">
        <x-table.columns>
            <x-table.column>Gift Card</x-table.column>
            <x-table.column>Quantity</x-table.column>
            <x-table.column>Amount</x-table.column>
            <x-table.column class="text-center">Delivery Time</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column>Date</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @forelse($orders as $order)
                <x-table.row class="cursor-pointer">
                    <x-table.cell>{{ $order->giftCard->name }}</x-table.cell>
                    <x-table.cell>{{ $order->quantity }}</x-table.cell>
                    <x-table.cell>{{ to_money($order->total_amount, 2, 'USDT ') }}</x-table.cell>
                    <x-table.cell>
                        @if($order->status === 'pending')
                            <x-countdown-timer :time-remaining="$order->delivery_time->diffInSeconds(now())" message="Ready for Delivery" />
                        @else
                            Delivered
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <flux:badge :color="$order->status->getFluxColor()">
                            {{ ucfirst($order->status->getLabel()) }}
                        </flux:badge>
                    </x-table.cell>
                    <x-table.cell>{{ $order->created_at->format('d M h:i A') }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="7" class="text-center">No orders yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    <div class="md:hidden">
        @forelse($orders as $order)
            <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Order ID:</flux:heading>
                    <flux:subheading class="text-end!">{{ $order->reference }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Gift Card:</flux:heading>
                    <flux:subheading class="text-end!">{{ $order->giftCard->name }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Quantity:</flux:heading>
                    <flux:subheading>{{ $order->quantity }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount:</flux:heading>
                    <flux:subheading>{{ to_money($order->total_amount, 2, 'USDT ') }}</flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Delivery Time:</flux:heading>
                    <flux:subheading>
                        @if($order->status === 'pending')
                            <x-countdown-timer :time-remaining="$order->delivery_time->diffInSeconds(now())" message="Ready for Delivery" />
                        @else
                            Delivered
                        @endif
                    </flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Status:</flux:heading>
                    <flux:badge :color="$order->status->getFluxColor()">
                        {{ ucfirst($order->status->getLabel()) }}
                    </flux:badge>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Date:</flux:heading>
                    <flux:subheading>{{ $order->created_at->format('d M h:i A') }}</flux:subheading>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No orders yet!</div>
        @endforelse
    </div>

    @if(!$limit)
        <div class="my-2">
            {{ $orders->links() }}
        </div>
    @endif
</div>
