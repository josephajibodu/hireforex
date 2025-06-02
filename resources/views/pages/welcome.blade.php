<x-layouts.guest>
    @section('title', 'Access the global arbitrage market')

    <!-- Hero Section -->
    <section class="w-full px-3 antialiased bg-white lg:px-6 border-b">
        <div class="mx-auto max-w-screen-xl border-x">
            <div class="container px-6 py-24 md:py-32 mx-auto text-center md:px-4">
                <h1 class="text-3xl font-extrabold leading-none tracking-tight text-black sm:text-4xl md:text-5xl">
                    <span class="block">Access the Global </span>
                    <span class="relative inline-block mt-3 text-black">Arbitrage Market with</span>
                    <span class="block mt-3 text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-700">Profitchain</span>
                </h1>
                <p class="mx-auto mt-6 text-base text-gray-700 text-center md:mt-8 sm:text-lg md:max-w-xl md:text-xl">
                    Trade in the global arbitrage market with Profitchain and earn resonable profit margin.
                </p>
                <div class="flex items-center justify-center mt-8 space-y-4 gap-4 md:space-y-0 md:space-x-4 md:flex-row">
                    <flux:button href="{{ route('register') }}" variant="primary" class="mb-0">
                        Create Your Account
                    </flux:button>
                    <flux:button href="{{ route('login') }}">
                        Login
                    </flux:button>
                </div>
                 <p class="mx-auto mt-6 text-base text-gray-700 text-center md:mt-8 sm:text-lg md:max-w-xl md:text-xl">
                    Profitchain is a platform for currency arbitrage, allowing users to buy USD, trade it for profit, and withdraw earnings in Naira. <br>


                </p>
            </div>
        </div>
    </section>

    <!-- Why Profitchain Section -->
    <section class="py-16 bg-gray-50 border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Why Profitchain?</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Profitchain is the 1st platform in Africa to provide private and exclusive access to the global arbitrage market, you can buy USD, trade in the arbitrage market, and withdraw your earnings on Profitchain.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Easy Trading</h3>
                    <p class="text-gray-600">Select profitable arbitrage currency pairs and let our system handle the trade.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Fast Payouts</h3>
                    <p class="text-gray-600">Withdraw your earnings directly to your Nigerian bank account.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Exclusive Bonus</h3>
                    <p class="text-gray-600">Get $5 free upon registration (after KYC verification).</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Transparent System</h3>
                    <p class="text-gray-600">View live trades, proof of payouts, and dispute resolution systems.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 md:py-32 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">How it works</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Our automated system ensures that your arbitrage trades are executed efficiently, generating returns within  4 to 9 hours.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3 px-4">
                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">1</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Fund Your Account</h3>
                    <p class="text-gray-600">Buy USD from verified dealers and fund your balance.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">2</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Arbitrage Trading</h3>
                    <p class="text-gray-600">Choose your preferred arbitrage currency combination, execute your trade, and let Profitchain manage the entire transaction for you.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">3</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Withdraw Your Earnings</h3>
                    <p class="text-gray-600">Your earnings are credited to your  balance, allowing you to easily transfer funds into your Naira account whenever you choose.</p>
                </div>
            </div>

            <!-- Example Trade -->
            <div class="mt-16 p-8 bg-brand-50 rounded-none border border-brand-100 max-w-screen-md mx-auto">
                <h3 class="text-2xl font-bold text-center mb-6">For Example</h3>
                <div class="grid grid-cols-3 md:grid-cols-7 gap-4 place-items-center gap-y-4 md:gap-y-0">
                    <div class="text-center px-4">
                        <div class="text-lg sm:text-xl font-bold whitespace-nowrap">Buy USD</div>
                        <div class="text-3xl font-bold text-brand-600">$50</div>
                    </div>

                    <div class="block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>

                    <div class="text-center px-4 flex flex-col items-center">
                        <div class="text-lg sm:text-xl font-bold">Trade Barbados Dollar to  Nepal Rupee (₨)</div>
                        <div class="text-3xl font-bold text-brand-600"> with $50</div>
                    </div>

                    <div class="hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>

                    <div class="text-center px-4">
                        <div class="text-lg sm:text-xl font-bold">Arbitrage Margin</div>
                        <div class="text-3xl font-bold text-green-600">13%</div>
                    </div>

                    <div class="block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>

                    <div class="text-center px-4">
                        <div class="text-lg sm:text-xl font-bold">Get</div>
                        <div class="text-3xl font-bold text-brand-600">$56.5</div>
                    </div>
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
                    Everything you need to know about Profitchain
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
        <div class="container px-4 mx-auto max-w-screen-lg">
            <livewire:general.arbitrage-trades />

            <livewire:general.buy-orders />
        </div>
    </section>

    <!-- Community Section -->
    <section class="py-16 md:py-32 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Join the Profitchain Community</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Stay updated and connect with other traders!
                </p>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <flux:button variant="primary" href="{{ route('community') }}" icon="">
                    Join our communities
                </flux:button>

                <flux:button href="{{ route('register') }}" icon="">
                    Create your account
                </flux:button>
            </div>
        </div>
    </section>

</x-layouts.guest>