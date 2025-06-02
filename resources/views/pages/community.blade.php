<x-layouts.guest>
    @section('title', 'Join our communities')

    <section class="overflow-hidden bg-accent">
        <div class="mx-auto max-w-screen-lg">
            <div class="container py-36 !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                    <div class="md:w-7/12 lg:w-6/12 xl:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
                        <h1 class="text-[calc(1.365rem_+_1.38vw)] font-bold leading-[1.2] xl:text-[2.4rem] !mb-3 text-white">
                            Join our Communities
                        </h1>
                        <p class="lead text-white/80 lg:!px-[1.25rem] xl:!px-[1.25rem] xxl:!px-[2rem] leading-[1.65] text-[0.9rem] font-medium !mb-[.25rem]">
                            Connect with other Profitchain users and stay updated
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-16 md:pb-32">
        <div class="prose max-w-none">
            <h2 class="mb-8 text-3xl font-bold text-center">Join profitchain WhatsApp community</h2>

            @php
                $community_links = app(\App\Settings\GeneralSetting::class)->community_links;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($community_links as $link)
                    <div class="bg-white rounded-lg shadow-md p-6 transition-all duration-300 hover:shadow-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-accent" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m14.72 8.79l-4.29 4.3l-1.65-1.65a1 1 0 1 0-1.41 1.41l2.35 2.36a1 1 0 0 0 .71.29a1 1 0 0 0 .7-.29l5-5a1 1 0 0 0 0-1.42a1 1 0 0 0-1.41 0M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">{{ $link['name'] }}</h3>
                                <a
                                        class="text-accent hover:text-accent-dark transition-colors duration-200 inline-flex items-center"
                                        target="_blank"
                                        href="{{ $link['url'] }}"
                                >
                                    <span>{{ $link['label'] }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 space-y-4">
                <h2 class="text-2xl font-semibold text-center">Join Now</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <flux:button variant="primary" href="#" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                        Join WhatsApp Group
                    </flux:button>
                    <flux:button href="#" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                        Join WhatsApp Channel
                    </flux:button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>