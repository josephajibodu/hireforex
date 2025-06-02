<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Transfer USD</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Send USD to other users on profitchain.
            </flux:subheading>
        </div>

        <div class="border rounded-lg py-4 col-span-7">
            <livewire:transfers.list-transfers />
        </div>

    </div>
</x-layouts.app>