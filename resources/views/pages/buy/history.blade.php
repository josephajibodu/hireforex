<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Buy Orders</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Securely purchase USD from verified dealers in just a few steps.
            </flux:subheading>
        </div>

        <!-- Referral table -->
        <div class="mt-12">
            <flux:heading class="mb-4 text-xl! md:text-2xl!" size="lg">All Orders</flux:heading>

            <x-table class="bg-gray-50 shadow-lg hidden md:table">
                <x-table.columns>
                    <x-table.column >Dealer</x-table.column>
                    <x-table.column>Amount(USD)</x-table.column>
                    <x-table.column>Rate</x-table.column>
                    <x-table.column>Amount(Payable)</x-table.column>
                    <x-table.column class="text-center">Status</x-table.column>
                    <x-table.column class="text-center">Date</x-table.column>
                </x-table.columns>

                <x-table.rows x-data>
                    @forelse($orders as $order)
                        <x-table.row class="cursor-pointer" @click="Livewire.navigate('{{ route('buy.show', ['order' => $order->reference]) }}')">
                            <x-table.cell class="min-w-24 capitalize">{{ $order->sellAdvert->user->username }}</x-table.cell>
                            <x-table.cell class="">{{ to_money($order->coin_amount, 100, hideSymbol: true) }}</x-table.cell>
                            <x-table.cell class="">
                                @if($order->isUsdtPayment())
                                    -
                                @else
                                    {{ to_money($order->seller_unit_price) }}
                                @endif
                            </x-table.cell>
                            <x-table.cell class="">
                                @if($order->isUsdtPayment())
                                    USDT {{ to_money($order->coin_amount, 100, false) }}
                                @else
                                    {{ to_money($order->total_amount) }}
                                @endif
                            </x-table.cell>
                            <x-table.cell class="text-center">
                                <x-flux::badge color="{{ $order->status->getFluxColor() }}">
                                    {{ $order->status->getLabel() }}
                                </x-flux::badge>
                            </x-table.cell>
                            <x-table.cell class="text-center" >{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="6" class="text-center">No order yet!</x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-table.rows>
            </x-table>

            <!-- Mobile-friendly stacked layout -->
            <div class="md:hidden">
                @forelse($orders as $order)
                    <a href="{{ route('buy.show', ['order' => $order->reference]) }}" wire:navigate class="block bg-gray-50 border rounded-lg p-4 mb-4 cursor-pointer hover:border-brand-500 active:border-brand-500 active:border-2">
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Dealer:</flux:heading>
                            <flux:subheading class="capitalize">{{ $order->sellAdvert->user->username }}</flux:subheading>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Amount (USD):</flux:heading>
                            <flux:subheading>{{ to_money($order->coin_amount, 100, hideSymbol: true) }}</flux:subheading>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Rate:</flux:heading>
                            <flux:subheading>
                                @if($order->isUsdtPayment())
                                    -
                                @else
                                    {{ to_money($order->seller_unit_price) }}
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
                            <flux:heading class="font-semibold">Status:</flux:heading>
                            <flux:badge color="{{ $order->status->getFluxColor() }}">
                                {{ $order->status->getLabel() }}
                            </flux:badge>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Date:</flux:heading>
                            <flux:subheading>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</flux:subheading>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-gray-500">No order yet!</div>
                @endforelse
            </div>

            <div class="my-2">
                {{ $orders->links() }}
            </div>

        </div>

    </div>
</x-layouts.app>