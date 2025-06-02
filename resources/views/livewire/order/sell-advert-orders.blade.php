<?php

use Livewire\Volt\Component;
use App\Models\SellAdvert;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateSellAdvert;
use Livewire\Attributes\Validate;
use App\Models\Order;

new class extends Component {
    use WithPagination;

    public function confirmPaymentNotReceived(int $orderId)
    {
        $order = Order::query()->find($orderId);

        $order->update(['status' => \App\Enums\OrderStatus::PaymentNotReceived]);

        $this->dispatch("flash-success", message: "Buyer will be notified that you didn't receive payment.");
    }

    public function confirmPaymentReceived(\App\Actions\CompleteBuyOrder $completeBuyOrder, int $orderId)
    {
        $order = Order::query()->find($orderId);

        try {
            $completeBuyOrder->execute($order);

            $amount = to_money($order->coin_amount, 100, '$');

            $this->dispatch("flash-success", message: "{$amount} released to the buyer");

            $this->dispatch('order-completed');

        } catch (Exception $ex) {
            report($ex);

            $this->dispatch("flash-error", message: "Order could not be completed");
        }
    }

}; ?>

<div class="mt-12">
    <flux:heading class="text-xl! md:text-2xl! mb-4">All Orders</flux:heading>

    <x-table class="bg-gray-50 shadow-lg hidden md:table">
        <x-table.columns>
            <x-table.column >Buyer</x-table.column>
            <x-table.column class="text-center">Amount(USD)</x-table.column>
            <x-table.column>Amount(Payable)</x-table.column>
            <x-table.column>Payment Proof</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column>Expires At</x-table.column>
            <x-table.column class="text-center">Date</x-table.column>
            <x-table.column class="text-center">Actions</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @php
                $orders = Auth::user()->sellAdvert->orders()->with(['buyer', 'sellAdvert.user'])->latest()->paginate()
            @endphp
            @forelse($orders as $order)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">
                        <flux:heading>{{ $order->buyer->username }}</flux:heading>
                        <flux:subheading>{{ $order->buyer->full_name }}</flux:subheading>
                    </x-table.cell>
                    <x-table.cell class="">
                        <div class="text-center">
                            <flux:heading>{{ to_money($order->coin_amount, 100, '$') }}</flux:heading>
                            @if($order->isLocalPayment())
                                <flux:subheading>{{ to_money($order->seller_unit_price) }}/$</flux:subheading>
                            @endif
                        </div>
                    </x-table.cell>
                    <x-table.cell class="">
                        @if($order->isUsdtPayment())
                            USDT {{ to_money($order->coin_amount, 100, false) }}
                        @else
                            {{ to_money($order->total_amount) }}
                        @endif
                    </x-table.cell>
                    <x-table.cell class="">
                        <a target="_blank" href="{{ Storage::url($order->payment_proof) }}" class="underline">
                            View Proof
                        </a>
                    </x-table.cell>
                    <x-table.cell class="text-center">
                        <x-flux::badge color="{{ $order->status->getFluxColor() }}">
                            {{ $order->status->getLabel() }}
                        </x-flux::badge>
                    </x-table.cell>
                    <x-table.cell class="text-center" >
                        <flux:badge :color="$order->created_at->diffInMinutes(now()) > $order->payment_time_limit ? 'red' : 'green'">
                            {{ $order->created_at->addMinutes($order->payment_time_limit)->format('D, d M Y H:i:s') }}
                        </flux:badge>
                    </x-table.cell>
                    <x-table.cell class="text-center" >
                        {{ $order->created_at->format('d M h:i A') }}
                    </x-table.cell>
                    <x-table.cell class="text-center" >
                        <flux:dropdown>
                            <flux:button icon="ellipsis-horizontal" class="cursor-pointer"></flux:button>

                            <flux:menu>
                                @if(($order->getTimeLeft() === 0 && $order->isPending()) || $order->isPaid())
                                <flux:modal.trigger name="payment-not-received">
                                    <flux:menu.item class="cursor-pointer" variant="danger" icon="trash"
                                                    wire:click="confirmPaymentNotReceived({{ $order->id }})"
                                                    wire:confirm="Are you sure you have not received payment?">Payment Not Received</flux:menu.item>
                                </flux:modal.trigger>
                                @endif

                                @if($order->inPendingState())
                                <flux:modal.trigger name="payment-received">
                                    <flux:menu.item class="cursor-pointer" icon="check-circle"
                                                    wire:click="confirmPaymentReceived({{ $order->id }})"
                                                    wire:confirm="Are you sure you received the payment?">Payment Received</flux:menu.item>
                                </flux:modal.trigger>
                                @endif

                            </flux:menu>
                        </flux:dropdown>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="8" class="text-center">No order yet!</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    <div class="md:hidden">
        @forelse($orders as $order)
            <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                <div class="flex justify-between mb-1">
                    <flux:heading class="font-semibold">Buyer:</flux:heading>
                    <div class="capitalize text-right">
                        <span class="block text-sm">{{ $order->buyer->username }}</span>
                        <span class="block text-sm font-bold">{{ $order->buyer->full_name }}</span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount (USD):</flux:heading>
                    <flux:subheading class="text-end">
                        {{ to_money($order->coin_amount, 100, '$') }}
                        @if($order->isLocalPayment())
                            <flux:subheading class="italic">({{ to_money($order->seller_unit_price) }}/$)</flux:subheading>
                        @endif
                    </flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount (Payable):</flux:heading>
                    <flux:subheading>
                        @if($order->isUsdtPayment())
                            USDT {{ to_money($order->coin_amount, 100, false) }}
                        @else
                            {{ to_money($order->total_amount) }}
                        @endif
                    </flux:subheading>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Payment Proof:</flux:heading>
                    <a target="_blank" href="{{ Storage::url($order->payment_proof) }}" class="underline text-blue-600">View Proof</a>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Status:</flux:heading>
                    <flux:badge color="{{ $order->status->getFluxColor() }}">
                        {{ $order->status->getLabel() }}
                    </flux:badge>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Expires At:</flux:heading>
                    <flux:badge :color="$order->created_at->diffInMinutes(now()) > $order->payment_time_limit ? 'red' : 'green'">
                        {{ $order->created_at->addMinutes($order->payment_time_limit)->format('D, d M Y H:i:s') }}
                    </flux:badge>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Date:</flux:heading>
                    <flux:subheading>{{ $order->created_at->format('d M h:i A') }}</flux:subheading>
                </div>
                <div class="mt-4">
                    <flux:dropdown>
                        <flux:button icon="ellipsis-horizontal" class="cursor-pointer"></flux:button>
                        <flux:menu>
                            @if(($order->getTimeLeft() === 0 && $order->isPending()) || $order->isPaid())
                            <flux:modal.trigger name="payment-not-received">
                                <flux:menu.item class="cursor-pointer" variant="danger" icon="trash"
                                                wire:click="confirmPaymentNotReceived({{ $order->id }})"
                                                wire:confirm="Are you sure you have not received payment?">
                                    Payment Not Received
                                </flux:menu.item>
                            </flux:modal.trigger>
                            @endif

                            @if($order->inPendingState())
                                <flux:modal.trigger name="payment-received">
                                    <flux:menu.item class="cursor-pointer" icon="check-circle"
                                                    wire:click="confirmPaymentReceived({{ $order->id }})"
                                                    wire:confirm="Are you sure you received the payment?">
                                        Payment Received
                                    </flux:menu.item>
                                </flux:modal.trigger>
                            @endif
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No orders yet!</div>
        @endforelse
    </div>

    <div class="my-2">
        {{ $orders->links() }}
    </div>

</div>
