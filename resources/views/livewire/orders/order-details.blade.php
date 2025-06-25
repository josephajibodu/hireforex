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

                <div class="mt-6 font-medium">Order Details</div>
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
                                Expected Resale Value
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ to_money($order->getExpectedResaleValue(), currency: '$') }}
                            </div>
                        </div>
                    </div>
                    @if($order->status === OrderStatus::Completed && $order->resale_amount)
                    <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Resale Value Credited
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-green-600">
                                {{ to_money($order->resale_amount ?? 0, currency: '$') }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Completed At
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->completed_at?->format('d M Y H:i') ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($order->status === OrderStatus::Paid)
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border">
                        <div class="flex items-center gap-2 mb-2">
                            <flux:icon name="info" class="text-blue-600" />
                            <flux:heading class="text-blue-800 dark:text-blue-200">Processing Information</flux:heading>
                        </div>
                        <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <p>• Your gift card order is being processed by Cardbeta</p>
                            <p>• Cardbeta will purchase the gift card at a discounted rate</p>
                            <p>• After the delivery period, you'll receive <strong>{{ to_money($order->getExpectedResaleValue(), currency: '$') }}</strong> in your Cardbeta balance</p>
                            <p>• This represents your profit from the resale transaction</p>
                        </div>
                    </div>
                @endif

                @if($order->status === OrderStatus::Completed)
                    <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border">
                        <div class="flex items-center gap-2 mb-2">
                            <flux:icon name="check-circle" class="text-green-600" />
                            <flux:heading class="text-green-800 dark:text-green-200">Order Completed</flux:heading>
                        </div>
                        <div class="text-sm text-green-700 dark:text-green-300 space-y-1">
                            <p>• Your gift card has been successfully processed and resold</p>
                            <p>• <strong>{{ to_money($order->resale_amount ?? 0, currency: '$') }}</strong> has been credited to your Cardbeta balance</p>
                            <p>• You can now use these funds for withdrawals or other transactions</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-span-12 flex flex-col gap-y-10 xl:col-span-8">
            <div class="rounded-lg border p-5">
                <flux:heading size="lg" class="mb-4">Resale Transaction Summary</flux:heading>

                <div class="space-y-6">
                    <!-- Transaction Details -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <flux:heading class="mb-3">Transaction Details</flux:heading>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Gift Card:</span>
                                <span class="font-medium">{{ $order->giftCard->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-medium">{{ $order->quantity }}x</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Purchase Cost:</span>
                                <span class="font-medium text-red-600">-{{ to_money($order->total_amount, hideSymbol: true) }} USDT</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Expected Resale Value:</span>
                                <span class="font-medium text-green-600">+{{ to_money($order->getExpectedResaleValue(), currency: '$') }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-semibold">
                                <span>Expected Profit:</span>
                                <span class="text-green-600">+{{ to_money($order->getExpectedResaleValue() - $order->total_amount, currency: '$') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Processing Status -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <flux:heading class="mb-3">Processing Status</flux:heading>
                        <div class="space-y-2 text-sm">
                            @if($order->status === OrderStatus::Paid)
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                    <span>Processing gift card purchase and resale</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                    <span>Resale value will be credited in {{ $order->delivery_time->diffForHumans() }}</span>
                                </div>
                            @elseif($order->status === OrderStatus::Completed)
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-green-700">Transaction completed successfully</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-green-700">Resale value credited to your balance</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <flux:heading class="mb-3">Transaction Timeline</flux:heading>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                <div>
                                    <div class="font-medium text-sm">Order Placed</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i A') }}</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 {{ $order->status !== OrderStatus::Paid ? 'bg-gray-300' : 'bg-blue-500' }} rounded-full mt-2"></div>
                                <div>
                                    <div class="font-medium text-sm">Processing Period</div>
                                    <div class="text-xs text-gray-500">{{ $order->delivery_time->format('d M Y H:i A') }}</div>
                                </div>
                            </div>

                            @if($order->status === OrderStatus::Completed)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div>
                                        <div class="font-medium text-sm">Resale Completed</div>
                                        <div class="text-xs text-gray-500">{{ $order->completed_at->format('d M Y H:i A') }}</div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
