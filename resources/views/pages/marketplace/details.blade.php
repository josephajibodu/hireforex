<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Order Details</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Order for {{ $order->quantity }}x {{ $order->giftCard->name }} gift card
            </flux:subheading>
        </div>

        <livewire:orders.order-details :order="$order"  />
    </div>
</x-layouts.app>