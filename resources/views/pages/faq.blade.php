<x-layouts.guest>
    @section('title', 'Everything you need to know about HireForex')

    <section class="overflow-hidden bg-accent">
        <div class="mx-auto max-w-screen-lg">
            <div class="container py-36 !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                    <div class="md:w-7/12 lg:w-6/12 xl:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
                        <h1 class="text-[calc(1.365rem_+_1.38vw)] font-bold leading-[1.2] xl:text-[2.4rem] !mb-3 text-white">
                            Frequently Asked Questions
                        </h1>
                        <p class="lead text-white/80 lg:!px-[1.25rem] xl:!px-[1.25rem] xxl:!px-[2rem] leading-[1.65] text-[0.9rem] font-medium !mb-[.25rem]">
                            Everything you need to know about HireForex.com
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section
        x-data="{
            activeAccordion: '',
            setActiveAccordion(id) {
                this.activeAccordion = (this.activeAccordion == id) ? '' : id;
            }
        }"
        class="mx-auto max-w-4xl px-4 py-16 md:pb-32"
    >
        @foreach (config('faqs') as $question => $responses)
            @php $id = \Illuminate\Support\Str::uuid(); @endphp
            <div x-data="{ id: '{{ $id }}' }" :class="{ 'text-neutral-900': activeAccordion==id, 'text-neutral-600 hover:text-neutral-900': activeAccordion!=id }" class="cursor-pointer group">
                <button @click="setActiveAccordion(id)" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                    <flux:heading size="lg" class="cursor-pointer">{{ $question }}</flux:heading>
                    <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion==id }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                </button>
                <div x-show="activeAccordion==id" x-collapse x-cloak>
                    <div class="p-4 pt-2">
                        @if (is_array($responses))
                            @foreach ($responses as $response)
                                <flux:subheading size="lg" class="mb-2">{{ $response }}</flux:subheading>
                            @endforeach
                        @else
                            <flux:subheading size="lg">{{ $responses }}</flux:subheading>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </section>
</x-layouts.guest>
