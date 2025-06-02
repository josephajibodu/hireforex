<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $bank_name = '';
    public string $bank_account_name = '';
    public string $bank_account_number = '';
    public array $available_banks = [];

    /**
     * Mount the component with authenticated user's bank details.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->bank_name = $user->bank_name;
        $this->bank_account_name = $user->bank_account_name;
        $this->bank_account_number = $user->bank_account_number;

        $this->available_banks = config('banks');
    }

    /**
     * Update the bank details for the currently authenticated user.
     */
    public function updateBankDetails(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:10'],
        ]);

        $user->fill($validated);
        $user->save();

        $this->dispatch('bank-details-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="{{ __('Bank Details') }}" subheading="{{ __('Your bank details will be used to process your Naira withdrawal, make sure to input
the correct bank name, account name, and account number.') }}">
        <form wire:submit="updateBankDetails" class="my-6 w-full space-y-6">

            <flux:select wire:model="bank_name" label="Bank" placeholder="Select Bank ...">
                @foreach($available_banks as $bank)
                    <flux:select.option>{{ $bank['bank_name'] }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model="bank_account_name" label="{{ __('Account Name') }}" type="text" name="bank_account_name" disabled />
            <flux:input wire:model="bank_account_number" label="{{ __('Account Number') }}" type="text" name="bank_account_number" required />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
                <x-action-message class="me-3 text-green-600" on="bank-details-updated">
                    {{ __('Bank details updated.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
