<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item href="{{ route('settings.profile') }}" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.password') }}" wire:navigate>{{ __('Password') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading class="text-xl! md:text-2xl!">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading class="max-w-screen-md">{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-screen-md">
            {{ $slot }}
        </div>
    </div>
</div>
