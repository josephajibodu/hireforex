<x-layouts.app>
    @section('title', 'Settle disputes with any dealer')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading class="text-xl! md:text-2xl!" level="1">Dispute</flux:heading>
        <flux:subheading size="lg" class="mb-6">Do you have any issues with any dealer?</flux:subheading>

        <flux:separator variant="subtle" />

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg border">
            <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-gray-200">
                Do you have any issues with any dealer?
            </flux:heading>
            <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300 list-decimal pl-5">
                <li>Contact any of the Mediator below on WhatsApp to solve the issue immediately.</li>
                <li>Or you can fill in the dispute form below and upload necessary proof:</li>
            </ol>
        </div>

        <div class="mt-8 grid grid-cols-12 gap-8">
            <div class="border rounded-lg py-4 px-4 col-span-full sm:col-span-5">
                <livewire:dispute-form />
            </div>

            <div class="border rounded-lg px-4 py-4 col-span-full sm:col-span-7 ">
                <flux:heading>Mediators Whatsapp Number</flux:heading>
                <flux:subheading>+2348079532641</flux:subheading>
            </div>
        </div>

    </div>

</x-layouts.app>
