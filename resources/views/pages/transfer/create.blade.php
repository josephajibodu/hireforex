<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Transfer USD</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                Send USD to other users on profitchain.
            </flux:subheading>
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">
                Please note that all currency transfers are final and cannot be reversed.
            </flux:heading>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:transfers.create-form />
            </div>

            <div class="border rounded-lg py-4 col-span-full sm:col-span-7">
                <livewire:transfers.list-transfers :limit="3" />
            </div>
        </div>

    </div>
</x-layouts.app>