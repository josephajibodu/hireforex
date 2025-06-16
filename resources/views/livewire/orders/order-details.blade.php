<?php

use Livewire\Volt\Component;
use App\Models\Order;
use App\Enums\OrderStatus;

new class extends Component {
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->loadMissing('giftCardUnits', 'giftCard');
    }
}; ?>

<div>
    <div class="grid grid-cols-12 gap-x-6 gap-y-10 text-sm">
        <div class="col-span-12 flex flex-col gap-y-10 xl:col-span-4">
            <div class="border rounded-lg p-5">
                <div class="mb-5 border-b border-dashed pb-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="flex items-center">
                            <flux:heading class="text-accent">
                                {{ $order->quantity }}x {{ $order->giftCard->name }}
                            </flux:heading>
                        </div>
                        <div class="flex items-center rounded-full font-medium sm:ml-auto">
                            <flux:badge class="flex px-3 py-1 font-medium rounded-full"
                                      color="{{ $order->status->getFluxColor() }}">
                                {{ $order->status->getLabel() }}
                            </flux:badge>
                        </div>
                    </div>
                </div>
                <div>
                    <flux:heading>Total Amount</flux:heading>
                    <div class="mt-1 flex items-center">
                        <flux:subheading class="font-medium">{{ to_money($order->total_amount, hideSymbol: true) }} USDT</flux:subheading>
                    </div>
                </div>

                @if($order->status === OrderStatus::Paid)
                    <div class="py-4 mb-4 flex justify-between border-b border-dashed">
                        <div class="text-slate-500">Time Limit</div>
                        <x-countdown-timer :time-remaining="$order->getTimeLeft()" message="Time elapsed"/>
                    </div>
                @endif


                <div class="mt-6 font-medium">Gift Card Details</div>
                <div class="mt-4 flex flex-col rounded-[0.6rem] border border-dashed border-slate-300/80">
                    <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Delivery Duration
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->giftCard->delivery_duration }} hours
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Resell Value
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ to_money($order->giftCard->resell_value) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Created
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 flex flex-col gap-y-10 xl:col-span-8">
            <div class="rounded-lg border p-5">
                <flux:heading size="lg" class="mb-4">Gift Card Codes</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($order->giftCardUnits as $i => $unit)
                        @php
                            $delivered = now()->greaterThanOrEqualTo($order->delivery_time) && $order->status === OrderStatus::Completed;
                        @endphp
                        <div class="rounded-lg border bg-gray-50 p-4 flex flex-col gap-2 relative">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-700">Gift Card #{{ $i + 1 }}</span>
                                <flux:badge color="{{ $delivered ? 'success' : 'warning' }}">
                                    {{ $delivered ? 'Delivered' : 'Locked' }}
                                </flux:badge>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <flux:input
                                    value="{{ $delivered ? $unit->code : Str::mask($unit->code, '*', 0) }}"
                                    readonly
                                    copyable
                                    icon="key"
                                    :invalid="!$delivered"
                                    :type="$delivered ? 'text' : 'password'"
                                    :inputmode="$delivered ? 'text' : 'none'"
                                    :disabled="!$delivered"
                                    placeholder="Locked"
                                />
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                @if($delivered)
                                    Delivered {{ $order->delivery_time->diffForHumans() }}
                                @else
                                    Available {{ $order->delivery_time->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
