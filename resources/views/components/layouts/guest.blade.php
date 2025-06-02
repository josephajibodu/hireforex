<x-layouts.app.static>
    @push('meta')
        <meta name="robots" content="index, follow">
    @endpush

    <div class="p-0! border-b">
        {{ $slot }}
    </div>
</x-layouts.app.static>
