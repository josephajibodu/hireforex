<x-layouts.guest>
    @section('title', 'Buy Gift Cards Online at the Best Rates | Secure & Fast')

    <!-- Hero Section -->
    <section class="w-full px-3 antialiased bg-white lg:px-6 border-b">
        <div class="mx-auto max-w-screen-xl border-x">
            <div class="container px-6 py-24 md:py-32 mx-auto text-center md:px-4">
                <h1 class="text-3xl font-extrabold leading-none tracking-tight text-black sm:text-4xl md:text-5xl">
                    <span class="block">Buy Gift Cards Instantly</span>
                    <span class="relative inline-block mt-3 text-black">with</span>
                    <span class="block mt-3 text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-700">Cardbeta</span>
                </h1>
                <p class="mx-auto mt-6 text-base text-gray-700 text-center md:mt-8 sm:text-lg md:max-w-xl md:text-xl">
                    Cardbeta is a secure and user-friendly platform that allows you to buy gift cards and e-cards at the best rates. Get instant access to discounted digital cards from top global brands, with a smooth and transparent experience.
                </p>
                <div class="flex items-center justify-center mt-8 space-y-4 gap-4 md:space-y-0 md:space-x-4 md:flex-row">
                    <flux:button href="{{ route('register') }}" variant="primary" class="mb-0">
                        Create Your Account
                    </flux:button>
                    <flux:button href="{{ route('login') }}">
                        Login
                    </flux:button>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Cardbeta Section -->
    <section class="py-16 bg-gray-50 border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Why Cardbeta?</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Cardbeta connects you to the best deals on digital gift cards from trusted brands, with instant delivery and unbeatable support.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Secure Platform</h3>
                    <p class="text-gray-600">Your transactions and data are protected with industry-leading security.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Best Rates</h3>
                    <p class="text-gray-600">Get the most competitive prices on all your favorite gift cards.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Instant Delivery</h3>
                    <p class="text-gray-600">Receive your gift card codes instantly after purchase, 24/7.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Trusted Brands</h3>
                    <p class="text-gray-600">Shop cards from Amazon, iTunes, Google Play, Steam, and more top brands.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 md:py-32 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">How Cardbeta Works</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Buying digital gift cards is easy, fast, and secure with Cardbeta.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-4 px-4">
                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">1</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Fund Your Wallet</h3>
                    <p class="text-gray-600">Add funds using Bybit, USDT, or other supported payment methods.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">2</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Browse & Buy</h3>
                    <p class="text-gray-600">Explore a wide range of gift cards and choose the ones you want.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">3</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Receive Instantly</h3>
                    <p class="text-gray-600">Get your digital code delivered instantly to your dashboard.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">4</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Track Your Orders</h3>
                    <p class="text-gray-600">Monitor your purchases and order status in real time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-white border-b">
        <div
            x-data="{
                activeAccordion: '',
                setActiveAccordion(id) {
                    this.activeAccordion = (this.activeAccordion == id) ? '' : id;
                }
            }"
            class="relative w-full max-w-screen-lg mx-auto text-sm font-normal py-16 md:py-32 px-4 md:px-8 border-x"
        >
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Frequently Asked Questions</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Everything you need to know about Cardbeta and digital gift cards.
                </p>
            </div>

            @foreach (config('faqs') as $question => $responses)
                @php $id = \Illuminate\Support\Str::uuid(); @endphp
                <div x-data="{ id: '{{ $id }}' }" :class="{ 'text-neutral-900': activeAccordion==id, 'text-neutral-600 hover:text-neutral-900': activeAccordion!=id }" class="cursor-pointer group">
                    <button @click="setActiveAccordion(id)" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                        <flux:heading size="lg">{{ $question }}</flux:heading>
                        <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion==id }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion==id" x-collapse x-cloak>
                        <div class="p-4 pt-2">
                            @if (is_array($responses))
                                @foreach ($responses as $response)
                                    <flux:subheading size="lg" class="mb-2 dark:text-zinc-800!">{{ $response }}</flux:subheading>
                                @endforeach
                            @else
                                <flux:subheading size="lg">{{ $responses }}</flux:subheading>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <livewire:giftcards.available-giftcards limit="10" showHeader="{{ false }}" guest="{{ true }}" />

            <livewire:general.giftcard-orders />
        </div>
    </section>

    <!-- Community Section -->
    <section class="py-16 md:py-32 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Join the Cardbeta Community</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Stay updated and connect with other gift card buyers and sellers!
                </p>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <flux:button href="{{ route('register') }}" icon="">
                    Create your account
                </flux:button>
            </div>
        </div>
    </section>

</x-layouts.guest>