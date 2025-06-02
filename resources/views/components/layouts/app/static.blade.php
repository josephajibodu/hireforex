<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <header class="w-full px-3 antialiased bg-white border-b lg:px-6">
            <div class="mx-auto max-w-screen-xl">
                <nav class="flex items-center w-full h-24 select-none" x-data="{ showMenu: false }">
                    <div class="relative flex flex-wrap items-start justify-between w-full mx-auto font-medium md:items-center md:h-24 md:justify-between">
                        <a href="{{ url('/') }}" class="flex items-center w-1/4 py-4 pl-6 pr-4 space-x-2 font-extrabold text-black md:py-0">
                            <x-app-logo-full />
                        </a>
                        <div :class="{'flex': showMenu, 'hidden md:flex': !showMenu }" class="absolute z-50 flex-col items-center justify-center w-full h-auto px-8 text-center text-gray-400 -translate-x-1/2 border-0 border rounded-full md:border md:w-auto md:h-10 left-1/2 md:flex-row md:items-center">
                            <a href="{{ url('/') }}"
                               wire:navigate
                               class="{{ request()->routeIs('home') ? 'text-accent' : '' }} relative inline-block w-full h-full px-4 py-5 mx-2 font-medium leading-tight text-center md:py-2 group md:w-auto md:px-2 lg:mx-3 md:text-center">
                                <span>Home</span>
                                <span class="absolute bottom-0 left-0 h-px duration-300 ease-out translate-y-px bg-gradient-to-r md:from-gray-200 md:via-accent md:to-gray-200 from-gray-900 via-gray-600 to-gray-900
                                    {{ request()->routeIs('home') ? 'w-full left-0' : 'w-0 left-1/2 group-hover:w-full group-hover:left-0' }}">
                                </span>
                            </a>

                            <a href="{{ route('faq') }}"
                               wire:navigate
                               class="{{ request()->routeIs('faq') ? 'text-accent' : '' }} relative inline-block w-full h-full px-4 py-5 mx-2 font-medium leading-tight text-center duration-300 ease-out md:py-2 group hover:text-accent md:w-auto md:px-2 lg:mx-3 md:text-center">
                                <span>FAQ</span>
                                <span class="absolute bottom-0 h-px duration-300 ease-out translate-y-px bg-gradient-to-r md:from-gray-200 md:via-accent md:to-gray-200 from-gray-900 via-gray-600 to-gray-900
                                    {{ request()->routeIs('faq') ? 'w-full left-0' : 'w-0 left-1/2 group-hover:w-full group-hover:left-0' }}">
                                </span>
                            </a>

                            <a href="{{ route('payouts') }}"
                               wire:navigate
                               class="{{ request()->routeIs('payouts') ? 'text-accent' : '' }} relative inline-block w-full h-full px-4 py-5 mx-2 font-medium leading-tight text-center duration-300 ease-out md:py-2 group hover:text-accent md:w-auto md:px-2 lg:mx-3 md:text-center">
                                <span>Payouts</span>
                                <span class="absolute bottom-0 h-px duration-300 ease-out translate-y-px bg-gradient-to-r md:from-gray-200 md:via-accent md:to-gray-200 from-gray-900 via-gray-600 to-gray-900
                                    {{ request()->routeIs('payouts') ? 'w-full left-0' : 'w-0 left-1/2 group-hover:w-full group-hover:left-0' }}">
                                </span>
                            </a>

                            <a href="{{ route('contact-us') }}"
                               wire:navigate
                               class="{{ request()->routeIs('contact-us') ? 'text-accent' : '' }} relative inline-block w-full h-full px-4 py-5 mx-2 font-medium leading-tight text-center duration-300 ease-out md:py-2 group hover:text-accent md:w-auto md:px-2 lg:mx-3 md:text-center">
                                <span>Contact</span>
                                <span class="absolute bottom-0 h-px duration-300 ease-out translate-y-px bg-gradient-to-r md:from-gray-200 md:via-accent md:to-gray-200 from-gray-900 via-gray-600 to-gray-900
                                    {{ request()->routeIs('contact-us') ? 'w-full left-0' : 'w-0 left-1/2 group-hover:w-full group-hover:left-0' }}">
                                </span>
                            </a>
                        </div>
                        <div class="fixed top-0 left-0 z-40 items-center hidden w-full h-full p-3 text-sm bg-accent bg-opacity-50 md:w-auto md:bg-transparent md:p-0 md:relative md:flex" :class="{'flex': showMenu, 'hidden': !showMenu }">
                            <div class="flex-col items-center w-full h-full p-3 overflow-hidden bg-white bg-opacity-50 rounded-lg select-none md:p-0 backdrop-blur-lg md:h-auto md:bg-transparent md:rounded-none md:relative md:flex md:flex-row md:overflow-auto">
                                <div class="flex flex-col items-center justify-end w-full h-full pt-2 md:w-full md:flex-row md:py-0">
                                    @guest()
                                        <flux:button variant="ghost" href="{{ route('login') }}" wire:navigate class="w-full">Sign In</flux:button>
                                        <flux:button variant="primary" href="{{ route('register') }}" wire:navigate class="w-full">Sign Up</flux:button>
                                    @endguest

                                    @auth()
                                        <flux:button variant="primary" href="{{ route('dashboard') }}" wire:navigate class="w-full">Dashboard</flux:button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        <div @click="showMenu = !showMenu" class="absolute right-0 z-50 flex flex-col items-end translate-y-1.5 w-10 h-10 p-2 mr-4 rounded-full cursor-pointer md:hidden hover:bg-gray-200/10 hover:bg-opacity-10" :class="{ 'text-accent': showMenu, 'text-accent': !showMenu }">
                            <svg class="w-6 h-6" x-show="!showMenu" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                                <path d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg class="w-6 h-6" x-show="showMenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        {{ $slot }}

        <x-footer.guest-footer />

        @fluxScripts
    </body>
</html>
