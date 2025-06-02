<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading size="xl">My Trade History</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">

            </flux:subheading>
        </div>

        <livewire:trade.list-all-trades />
    </div>
</x-layouts.app>