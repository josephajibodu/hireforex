<x-layouts.guest>
    @section('title', 'Contact Us')

    <section class="overflow-hidden bg-accent">
        <div class="mx-auto max-w-screen-lg">
            <div class="container py-36 !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                    <div class="md:w-7/12 lg:w-6/12 xl:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
                        <h1 class="text-[calc(1.365rem_+_1.38vw)] font-bold leading-[1.2] xl:text-[2.4rem] !mb-3 text-white">
                            Contact Us
                        </h1>
                        <p class="lead text-white/80 lg:!px-[1.25rem] xl:!px-[1.25rem] xxl:!px-[2rem] leading-[1.65] text-[0.9rem] font-medium !mb-[.25rem]">
                            Get in touch with our support team for any inquiries or assistance.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-16 md:pb-32">
        <div class="prose max-w-none">
            <div class="space-y-8">
                <section>
                    <h2 class="mb-3 text-2xl font-semibold">Customer Support</h2>
                    <p>
                        For general inquiries, account issues, or trading support, reach out to our support team via:
                    </p>
                    <ul class="list-none space-y-2 pl-0">
                        <li>
                            <strong>Email:</strong>
                            <a href="mailto:help@profitchain.com" class="text-accent hover:text-blue-800">help@profitchain.com</a>
                        </li>
                        <li>
                            <strong>WhatsApp:</strong> +2348079532641
                        </li>
                        <li>
                            <strong>Support Hours:</strong> Monday – Sunday (24/7)
                        </li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-3 text-2xl font-semibold">WhatsApp Support (Quick Assistance)</h2>
                    <p>
                        For faster responses, contact our support agents via WhatsApp:
                    </p>
                    <p>
                        <strong>WhatsApp:</strong> +2348079532641
                    </p>
                </section>
            </div>

            <div class="mt-8 space-y-4">
                <h2 class="text-xl font-semibold">Get in Touch</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:button variant="primary" href="mailto:support@profitchain.com">
                        Email Support
                    </flux:button>
                    <flux:button href="https://wa.me/234XXXXXXXXXX" target="_blank" rel="noopener noreferrer">
                        WhatsApp Support
                    </flux:button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>