<x-layouts.app>
    <div class="max-w-screen-xl y-10">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <flux:heading class="text-xl! md:text-2xl!">Top Up History</flux:heading>
            <flux:subheading class="text-gray-600 dark:text-gray-400 text-sm">
                View all your top-up transactions and their status.
            </flux:subheading>
        </div>

        <div class="border rounded-lg py-4">
            <livewire:top-up.history />
        </div>
    </div>
</x-layouts.app>
