<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Order Details</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Order to buy {{ to_money($order->coin_amount, 100, currency: '$') }} into your main wallet from Dealer <b>{{ "{$dealer->username}" }}</b>
            </flux:subheading>
        </div>

{{--        <livewire:order.order-details :order="$order"  />--}}
    </div>
</x-layouts.app>
