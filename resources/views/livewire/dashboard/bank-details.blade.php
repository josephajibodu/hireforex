<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public bool $showModal = false;

    public string $bank_name = '';
    public string $bank_account_name = '';
    public string $bank_account_number = '';
    public array $available_banks = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->bank_name = $user->bank_name ?? '';
        $this->bank_account_name = $user->bank_account_name ?? '';
        $this->bank_account_number = $user->bank_account_number ?? '';

        $this->available_banks = config('banks');

        if (!$this->bank_name || !$this->bank_account_name || !$this->bank_account_number) {
            $this->showModal = true;
        }
    }

    public function saveBankDetails(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:20'],
        ]);

        $user->update($validated);
        $this->dispatch('bank-details-updated');

        $this->showModal = false;
    }

    public function checkBankDetails()
    {
        $user = Auth::user();

        if (!$user->bank_name || !$user->bank_account_name || !$user->bank_account_number) {
            $this->showModal = true;
        }
    }
}; ?>

<div>
    <flux:modal name="add-bank-details" class="w-full" wire:model.self="showModal" :dismissible="false" wire:close="checkBankDetails">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Complete your bank details (For Naira Withdrawals)</flux:heading>
                <flux:subheading>Your bank details will be used to process your Naira withdrawal, make sure to input
                    the correct bank name, account name, and account number.</flux:subheading>
            </div>

            <form wire:submit="saveBankDetails" class="mt-6 space-y-6">

                <flux:select wire:model="bank_name" label="Bank" placeholder="Select Bank ...">
                    @foreach($available_banks as $bank)
                        <flux:select.option>{{ $bank['bank_name'] }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="bank_account_name" label="Account Name" placeholder="Account Name" />
                <flux:input wire:model="bank_account_number" label="Account Number" placeholder="Account Number" type="text" />

                <div class="flex items-center">
                    <x-action-message class="me-3 text-green-600" on="bank-details-updated">
                        {{ __('Bank details updated successfully.') }}
                    </x-action-message>
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>

        </div>
    </flux:modal>
</div>
