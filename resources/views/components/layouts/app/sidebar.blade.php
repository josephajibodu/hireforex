<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="Platform" class="grid mb-8">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                    <flux:navlist.item icon="chart-candlestick" :href="route('marketplace.index')" :current="request()->routeIs('marketplace.index')" wire:navigate>{{ __('Marketplace') }}</flux:navlist.item>

                    <flux:navlist.item icon="wallet" :href="route('withdrawal.create')" :current="request()->routeIs('withdrawal.create')" wire:navigate>{{ __('Withdraw (Naira)') }}</flux:navlist.item>

                    <flux:navlist.item icon="arrow-right-left" :href="route('transfer.create')" :current="request()->routeIs('transfer.create')" wire:navigate>{{ __('Transfer USD') }}</flux:navlist.item>

                    <flux:navlist.item icon="coins" :href="route('buy-usdt.create')" :current="request()->routeIs('buy-usdt.create')" wire:navigate>{{ __('Withdraw USDT(Tether)') }}</flux:navlist.item>

                    <flux:navlist.item icon="scale" :href="route('dispute')" :current="request()->routeIs('dispute')" wire:navigate>{{ __('Dispute') }}</flux:navlist.item>
                    <flux:navlist.item icon="heart-handshake" :href="route('rewards')" :current="request()->routeIs('rewards')" wire:navigate>{{ __("Race to Top 4") }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('settings.referrals')" :current="request()->routeIs('settings.referrals')" wire:navigate>{{ __("Refer a Friend") }}</flux:navlist.item>
                </flux:navlist.group>

                @can(\App\Enums\SystemPermissions::CreateSellOrder->value)
                    <flux:navlist.group heading="Dealer" class="grid">
                        <flux:navlist.item icon="house" :href="route('dealer-dashboard')" :current="request()->routeIs('dealer-dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="megaphone" :href="route('sell.create-sell-order')" :current="request()->routeIs('sell.create-sell-order')" wire:navigate>{{ __('My Sell Order') }}</flux:navlist.item>
                        <flux:navlist.item icon="notebook-tabs" :href="route('sell.history')" :current="request()->routeIs('sell.history')" wire:navigate>{{ __('Sell Order History') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endcan
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">

            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->full_name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->full_name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->username }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->full_name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->username }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @stack('scripts')
        @fluxScripts

        <script>
            document.addEventListener('livewire:init', () => {
                const flashMessages = {
                    'flash-success': { title: 'Success!', icon: 'success' },
                    'flash-error': { title: 'Error!', icon: 'error' },
                    'flash-info': { title: 'Info!', icon: 'info' }
                };

                Object.entries(flashMessages).forEach(([eventName, config]) => {
                    Livewire.on(eventName, (event) => {
                        Swal.fire({
                            title: event.title ?? config.title,
                            text: event.message,
                            icon: config.icon,
                            confirmButtonText: 'OK'
                        });
                    });
                });

            });

            document.addEventListener('DOMContentLoaded', function () {
                // Laravel session flash messages
                const flashMessages = {
                    error: "{{ session('error') }}",
                    success: "{{ session('success') }}",
                    info: "{{ session('info') }}",
                    warning: "{{ session('warning') }}"
                };

                Object.entries(flashMessages).forEach(([key, message]) => {
                    if (message) {
                        Swal.fire({
                            title: key === 'error' ? 'Ooops!!!' : key.charAt(0).toUpperCase() + key.slice(1),
                            text: message,
                            icon: key,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#395094'
                        });
                    }
                });

                // Laravel validation errors
                @if($errors->any())
                @foreach($errors->all() as $error)
                Swal.fire({
                    title: 'Ooops!!!',
                    text: "{{ $error }}",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#395094'
                });
                @endforeach
                @endif
            });
        </script>
    </body>
</html>
