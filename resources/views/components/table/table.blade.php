<div {{ $attributes->class(['w-full overflow-x-auto rounded-lg shadow-none border']) }}>
    <table class="w-full min-w-max border-collapse bg-white text-left text-sm text-gray-600">
        {{ $slot }}
    </table>
</div>