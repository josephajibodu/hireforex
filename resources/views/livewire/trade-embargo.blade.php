<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Flux\Flux;

new class extends Component {

    public User $user;

    public bool $limitReached = false;

    public function mount()
    {
        $this->user = Auth::user();

        $totalUSDOrder = $this->user->totalTradeCompleted();

        $this->limitReached = $totalUSDOrder >= 15;
    }
}; ?>

<div>
    @if($user->kyc && $user->kyc->isCompleted() && ! $limitReached)
        <div class="relative overflow-hidden rounded-xl border bg-red-50 px-4 py-4">
            <div class="flex items-center">
                <flux:heading class="text-red-500!">
                    Buy up to 30 USD or more to unlock your bonus.
                </flux:heading>
            </div>

            <img src="{{ asset('images/warning.png') }}" class="w-24 absolute -top-5 -right-2 rotate-[-30deg]" alt="kyc" />
        </div>
    @endif
</div>
