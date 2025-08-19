<x-layouts.guest>
    @section('title', 'HireForex - Hire Professional Forex Traders with Money-Back Guarantee')

    <!-- Hero Section -->
    <section class="w-full px-3 antialiased bg-white lg:px-6 border-b">
        <div class="mx-auto max-w-screen-xl border-x">
            <div class="container px-6 py-24 md:py-32 mx-auto text-center md:px-4">
                <h1 class="text-3xl font-extrabold leading-none tracking-tight text-black sm:text-4xl md:text-5xl">
                    <span class="block">Hire Professional</span>
                    <span class="relative inline-block mt-3 text-black">Forex Traders</span>
                    <span class="block mt-3 text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-700">with Money-Back Guarantee</span>
                </h1>
                <p class="mx-auto mt-6 text-base text-gray-700 text-center md:mt-8 sm:text-lg md:max-w-xl md:text-xl">
                    HireForex is the first forex trader marketplace that allows you to hire vetted professionals for short-term trading sessions. Get up to 170% returns with up to 100% money-back guarantee protection.
                </p>
                <div class="flex items-center justify-center mt-8 space-y-4 gap-4 md:space-y-0 md:space-x-4 md:flex-row">
                    <flux:button href="{{ route('register') }}" variant="primary" class="mb-0">
                        Start Trading Now
                    </flux:button>
                    <flux:button href="{{ route('login') }}">
                        Login
                    </flux:button>
                </div>
            </div>
        </div>
    </section>

    <!-- Why HireForex Section -->
    <section class="py-16 bg-gray-50 border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Why Choose HireForex?</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    HireForex makes forex trading accessible to everyone. Simply fund your account, choose a professional trader, and let them trade for you with guaranteed protection.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <flux:icon name="shield-check" class="w-6 h-6" />
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Money-Back Guarantee</h3>
                    <p class="text-gray-600">Up to 100% MBG protection ensures your capital is safe even if trades fail.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <flux:icon name="chart-candlestick" class="w-6 h-6" />
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Professional Traders</h3>
                    <p class="text-gray-600">All traders are vetted with proven track records and years of experience.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <flux:icon name="clock" class="w-6 h-6" />
                    </div>
                    <h3 class="mb-3 text-xl font-bold">Quick Sessions</h3>
                    <p class="text-gray-600">Short-term trading sessions (5-14 days) for faster returns and flexibility.</p>
                </div>

                <div class="p-6 bg-white rounded-none border">
                    <div class="w-12 h-12 mb-4 text-brand-600 bg-brand-100 rounded-full flex items-center justify-center">
                        <flux:icon name="wallet" class="w-6 h-6" />
                    </div>
                    <h3 class="mb-3 text-xl font-bold">USDT Payments</h3>
                    <p class="text-gray-600">Fast deposits and withdrawals using USDT with minimal transaction fees.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 md:py-32 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">How HireForex Works</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Getting started with professional forex trading is simple and secure.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-4 px-4">
                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">1</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Fund Your Account</h3>
                    <p class="text-gray-600">Top up your HireForex wallet using USDT via Bybit transfer or direct USDT transfer.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">2</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Choose a Trader</h3>
                    <p class="text-gray-600">Browse verified traders, check their track records, MBG rates, and potential returns.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">3</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Monitor Progress</h3>
                    <p class="text-gray-600">Track your trade in real-time and wait for the trading session to complete.</p>
                </div>

                <div class="relative p-6 bg-white rounded-none border border-gray-200">
                    <div class="absolute -top-5 -left-5 w-10 h-10 rounded-none bg-brand-100 border border-accent text-accent flex items-center justify-center font-bold text-lg">4</div>
                    <h3 class="mb-3 text-xl font-bold pt-4">Get Returns</h3>
                    <p class="text-gray-600">Receive your profits or MBG refund directly to your wallet after completion.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Traders Section -->
    <section class="py-16 bg-gray-50 border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <livewire:traders.available-traders limit="3" showHeader="{{ false }}" guest="{{ true }}" />
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
                    Everything you need to know about HireForex and professional forex trading.
                </p>
            </div>

            <div class="space-y-4">
                <div x-data="{ id: 'faq1' }" :class="{ 'text-neutral-900': activeAccordion=='faq1', 'text-neutral-600 hover:text-neutral-900': activeAccordion!='faq1' }" class="cursor-pointer group">
                    <button @click="setActiveAccordion('faq1')" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                        <flux:heading size="lg">What is HireForex?</flux:heading>
                        <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion=='faq1' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion=='faq1'" x-collapse x-cloak>
                        <div class="p-4 pt-2">
                            <flux:subheading size="lg">HireForex is the first forex trader marketplace that allows individuals to hire vetted forex traders for short-term trading sessions with money-back guarantees that protect your capital from significant loss.</flux:subheading>
                        </div>
                    </div>
                </div>

                <div x-data="{ id: 'faq2' }" :class="{ 'text-neutral-900': activeAccordion=='faq2', 'text-neutral-600 hover:text-neutral-900': activeAccordion!='faq2' }" class="cursor-pointer group">
                    <button @click="setActiveAccordion('faq2')" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                        <flux:heading size="lg">How does the Money-Back Guarantee work?</flux:heading>
                        <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion=='faq2' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion=='faq2'" x-collapse x-cloak>
                        <div class="p-4 pt-2">
                            <flux:subheading size="lg">If your trade fails, you still get back up to 100% of your capital depending on the trader's MBG offer. For example, with a 99% MBG, you get back $99 for every $100 invested even if the trade loses.</flux:subheading>
                        </div>
                    </div>
                </div>

                <div x-data="{ id: 'faq3' }" :class="{ 'text-neutral-900': activeAccordion=='faq3', 'text-neutral-600 hover:text-neutral-900': activeAccordion!='faq3' }" class="cursor-pointer group">
                    <button @click="setActiveAccordion('faq3')" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                        <flux:heading size="lg">How do I choose the right trader?</flux:heading>
                        <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion=='faq3' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion=='faq3'" x-collapse x-cloak>
                        <div class="p-4 pt-2">
                            <flux:subheading size="lg">Check their past performance (last 5 trades), MBG percentage, potential returns, experience level, and preferred trading pairs. Higher MBG offers better protection, while higher returns may mean lower MBG.</flux:subheading>
                        </div>
                    </div>
                </div>

                <div x-data="{ id: 'faq4' }" :class="{ 'text-neutral-900': activeAccordion=='faq4', 'text-neutral-600 hover:text-neutral-900': activeAccordion!='faq4' }" class="cursor-pointer group">
                    <button @click="setActiveAccordion('faq4')" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                        <flux:heading size="lg">How long do trading sessions last?</flux:heading>
                        <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion=='faq4' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion=='faq4'" x-collapse x-cloak>
                        <div class="p-4 pt-2">
                            <flux:subheading size="lg">Trading sessions range from 5 to 14 days, depending on the trader you choose. This allows for quick returns and flexibility in your investment strategy.</flux:subheading>
                        </div>
                    </div>
                </div>

                <div x-data="{ id: 'faq5' }" :class="{ 'text-neutral-900': activeAccordion=='faq5', 'text-neutral-600 hover:text-neutral-900': activeAccordion!='faq5' }" class="cursor-pointer group">
                    <button @click="setActiveAccordion('faq5')" class="flex items-center justify-between w-full p-4 pb-1 text-left select-none">
                        <flux:heading size="lg">What payment methods do you accept?</flux:heading>
                        <svg class="w-5 h-5 duration-300 ease-out" :class="{ '-rotate-[45deg]': activeAccordion=='faq5' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion=='faq5'" x-collapse x-cloak>
                        <div class="p-4 pt-2">
                            <flux:subheading size="lg">We accept USDT deposits via Bybit transfer or direct USDT transfer using TRC-20 or BEP-20 networks. Withdrawals are processed within 1 hour with a 3% transaction fee.</flux:subheading>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-32 bg-white border-b">
        <div class="container px-4 mx-auto max-w-screen-xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">Ready to Start Trading?</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Join thousands of users who are already earning with HireForex
                </p>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <flux:button href="{{ route('register') }}" variant="primary">
                    Create Your Account
                </flux:button>
                <flux:button href="{{ route('login') }}" variant="outline">
                    Login to Dashboard
                </flux:button>
            </div>
        </div>
    </section>

</x-layouts.guest>
