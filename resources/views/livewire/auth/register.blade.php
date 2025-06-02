<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Actions\CheckReferrer;
use App\Models\Wallet;
use App\Enums\WalletType;
use App\Actions\ProcessRegistrationBonus;
use Illuminate\Support\Str;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $username = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $whatsapp_number = '';
    public string $referral_code = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount()
    {
        $this->referral_code = request()->query('code') ?? '';
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(CheckReferrer $checkReferrer): void
    {
        $validated = $this->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:11', 'regex:/^(0[789][01]\d{8})$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:11', 'regex:/^(0[789][01]\d{8})$/'],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone_number.regex' => 'The phone number must be a valid Nigerian number, starting with 0 and followed by 10 digits.',
            'whatsapp_number.regex' => 'The WhatsApp number must be a valid Nigerian number, starting with 0 and followed by 10 digits.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        do {
            $code = Str::upper(Str::random(8));
        } while (User::query()->where('referral_code', $code)->exists());

        /** @var User $user */
        $user = User::query()->create([
            ...$validated,
            'referral_code' => Str::upper($code)
        ]);

        event(new Registered($user));

        Wallet::query()->create([
            'user_id' => $user->id
        ]);

        $checkReferrer->execute($user, $validated['referral_code']);

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Create an account" description="Enter your details below to create your account" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Username -->
        <flux:input
                wire:model="username"
                id="username"
                label="{{ __('Username') }}"
                type="text"
                name="username"
                required
                autocomplete="username"
                placeholder="Unique username"
        />

        <div class="grid grid-cols-2 gap-6">
            <!-- First Name -->
            <flux:input
                    wire:model="first_name"
                    id="first_name"
                    label="{{ __('First Name') }}"
                    type="text"
                    name="first_name"
                    required
                    autofocus
                    autocomplete="given-name"
                    placeholder="First name"
            />

            <!-- Last Name -->
            <flux:input
                    wire:model="last_name"
                    id="last_name"
                    label="{{ __('Last Name') }}"
                    type="text"
                    name="last_name"
                    required
                    autocomplete="family-name"
                    placeholder="Last name"
            />
        </div>

        <!-- Email Address -->
        <flux:input
                wire:model="email"
                id="email"
                label="{{ __('Email address') }}"
                type="email"
                name="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
        />

        <div class="grid grid-cols-2 gap-6">
            <!-- Phone Number -->
            <flux:input
                    wire:model="phone_number"
                    id="phone_number"
                    label="{{ __('Phone Number') }}"
                    type="text"
                    name="phone_number"
                    required
                    autocomplete="tel"
                    placeholder="Phone number"
            />

            <!-- WhatsApp Number -->
            <flux:input
                    wire:model="whatsapp_number"
                    id="whatsapp_number"
                    label="{{ __('WhatsApp Number') }}"
                    type="text"
                    name="whatsapp_number"
                    autocomplete="tel"
                    placeholder="WhatsApp number"
            />
        </div>

        <!-- Referral Code -->
        <flux:input
                wire:model="referral_code"
                id="referral_code"
                label="{{ __('Referral Code') }}"
                type="text"
                autocomplete="off"
                name="referral_code"
                placeholder="Enter referral code (optional)"
        />

        <!-- Password -->
        <flux:input
                wire:model="password"
                id="password"
                label="{{ __('Password') }}"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Password"
        />

        <!-- Confirm Password -->
        <flux:input
                wire:model="password_confirmation"
                id="password_confirmation"
                label="{{ __('Confirm password') }}"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm password"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Already have an account?
        <flux:link href="{{ route('login') }}" wire:navigate>Log in</flux:link>
    </div>
</div>
