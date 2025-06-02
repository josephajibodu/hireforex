<x-layouts.app.sidebar>
    @section('title', 'Personal Dashboard')
    @push('meta')
        <meta name="robots" content="noindex, nofollow">
    @endpush

    <flux:main class="overflow-y-auto p-0!">
        <div class="min-h-screen p-6 lg:p-8">
            {{ $slot }}
        </div>

        <x-footer.dashboard-footer />
    </flux:main>
</x-layouts.app.sidebar>
