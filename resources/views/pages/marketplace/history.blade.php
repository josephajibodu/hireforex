<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Order History</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Track your gift card orders and their delivery status.
            </flux:subheading>
        </div>

        <!-- Orders table -->
        <div class="mt-12">
            <flux:heading class="mb-4 text-xl! md:text-2xl!" size="lg">All Orders</flux:heading>

            <x-table class="bg-gray-50 shadow-lg hidden md:table">
                <x-table.columns>
                    <x-table.column>Gift Card</x-table.column>
                    <x-table.column>Quantity</x-table.column>
                    <x-table.column>Total Amount</x-table.column>
                    <x-table.column>Delivery Time</x-table.column>
                    <x-table.column class="text-center">Status</x-table.column>
                    <x-table.column class="text-center">Date</x-table.column>
                </x-table.columns>

                <x-table.rows x-data>
                    @forelse($orders as $order)
                        <x-table.row class="cursor-pointer" @click="Livewire.navigate('{{ route('marketplace.show', $order) }}')">
                            <x-table.cell class="min-w-24">{{ $order->giftCard->name }}</x-table.cell>
                            <x-table.cell>{{ $order->quantity }}</x-table.cell>
                            <x-table.cell>{{ to_money($order->total_amount, hideSymbol: true) }} USDT</x-table.cell>
                            <x-table.cell>{{ $order->delivery_time?->format('d M Y H:i') ?? 'Pending' }}</x-table.cell>
                            <x-table.cell class="text-center">
                                <x-flux::badge color="{{ $order->status->getFluxColor() }}">
                                    {{ $order->status->getLabel() }}
                                </x-flux::badge>
                            </x-table.cell>
                            <x-table.cell class="text-center">{{ $order->created_at->format('d M Y') }}</x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="6" class="text-center">No active orders!</x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-table.rows>
            </x-table>

            <!-- Mobile-friendly stacked layout -->
            <div class="md:hidden">
                @forelse($orders as $order)
                    <a href="{{ route('marketplace.show', $order) }}" wire:navigate class="block bg-gray-50 border rounded-lg p-4 mb-4 cursor-pointer hover:border-brand-500 active:border-brand-500 active:border-2">
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Gift Card:</flux:heading>
                            <flux:subheading>{{ $order->giftCard->name }}</flux:subheading>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Quantity:</flux:heading>
                            <flux:subheading>{{ $order->quantity }}</flux:subheading>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Total Amount:</flux:heading>
                            <flux:subheading>{{ to_money($order->total_amount, hideSymbol: true) }} USDT</flux:subheading>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Delivery Time:</flux:heading>
                            <flux:subheading>{{ $order->delivery_time?->format('d M Y H:i') ?? 'Pending' }}</flux:subheading>
                        </div>
                        <div class="flex justify-between py-1">
                            <flux:heading class="font-semibold">Status:</flux:heading>
                            <flux:badge color="{{ $order->status->getFluxColor() }}">
                                {{ $order->status->getLabel() }}
                            </flux:badge>
                        </div>
                        <div class="flex justify-between">
                            <flux:heading class="font-semibold">Date:</flux:heading>
                            <flux:subheading>{{ $order->created_at->format('d M Y') }}</flux:subheading>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-gray-500">No active orders!</div>
                @endforelse
            </div>

            <div class="my-2">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
