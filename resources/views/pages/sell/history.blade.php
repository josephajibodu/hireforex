<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading size="xl">Buy Orders</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Securely purchase USD from verified dealers in just a few steps.
            </flux:subheading>
        </div>

        <livewire:order.manage-sell-advert />

        <!-- Orders table -->
        <livewire:order.sell-advert-orders />

    </div>
</x-layouts.app>