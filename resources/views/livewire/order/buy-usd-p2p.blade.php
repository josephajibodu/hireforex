<?php

use Livewire\Volt\Component;
use App\Models\SellAdvert;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateBuyOrder;
use App\Enums\SellAdvertType;

new class extends Component {
    use WithPagination;

    public SellAdvert $sellAdvert;
    public float $amount;
    public float $amountToPay = 0;
    public float $rate = 0;

    public function mount()
    {
        $this->rate = getCurrentRate();
    }

    public function updatedAmount()
    {
        $this->amountToPay = floatval($this->amount ?? 0) * $this->rate;
    }

    public function viewAdvert(int $advertId)
    {
        $this->sellAdvert = SellAdvert::query()->findOrFail($advertId);

        Flux::modal('confirm')->show();
    }

    public function create(CreateBuyOrder $createBuyOrder)
    {
        if (!$this->amountToPay || !$this->sellAdvert) return;

        try {
            $user = Auth::user();

            $order = $createBuyOrder->execute($user, $this->sellAdvert->id, $this->amount);

            session()->flash("success", "Please proceed to making payment to the Dealers bank account");

            $this->redirect(route('buy.show', ['order' => $order->reference], absolute: false), navigate: true);

        } catch (Exception $ex) {
            report($ex);

            Flux::modal('confirm')->close();
            $this->dispatch("flash-error", message: $ex->getMessage() ?? 'Failed to create buy order');
        }
    }
}; ?>

<div>
    <flux:modal name="confirm" class="w-full md:max-w-screen-sm" variant="flyout">
        <div class="space-y-6">
            @if(!$sellAdvert)
                <div>
                    <flux:heading size="lg">Buy USD</flux:heading>
                    <flux:subheading>Buy USD from available Dealers</flux:subheading>
                </div>
            @else
                <div>
                    <flux:heading size="lg">Buy USD from {{ $sellAdvert->user->username }}</flux:heading>
                    <flux:subheading>Stick to the time limit.</flux:subheading>
                </div>

                <flux:input wire:model.live.debounce.1000="amount" type="number" step="0.01" label="Amount(USD)" placeholder="Amount(USD) to buy" />

                @if($sellAdvert->type === SellAdvertType::Usdt)
                    <div class="border p-4 rounded-lg">
                        <flux:subheading>Amount to Pay</flux:subheading>
                        <flux:heading size="xl">USDT {{ to_money($amount ?? 0, 1, false) }}</flux:heading>
                    </div>
                @else
                    <div class="border p-4 rounded-lg">
                        <flux:subheading>Amount to Pay</flux:subheading>
                        <flux:heading size="xl">{{ to_money($amountToPay, 1) }}</flux:heading>
                    </div>
                @endif

                @if($sellAdvert->status === \App\Enums\SellAdvertStatus::SoldOut)
                    <p class="text-sm text-red-500">The dealer is already sold out. Please choose another dealer or try again later.</p>
                @endif

                <div class="flex">
                    <flux:spacer />

                    @if($sellAdvert->status === \App\Enums\SellAdvertStatus::SoldOut)
                        <flux:button variant="primary" class="disabled:opacity-25!" disabled>Continue</flux:button>
                    @else
                        <flux:button variant="primary" wire:loading.attr="disabled" wire:click="create">Continue</flux:button>
                    @endif
                </div>
            @endif
        </div>
    </flux:modal>

    <!-- Available Dealers -->
    <div class="mt-8">
        <flux:heading class="text-xl! md:text-2xl! mb-4">Available Dealers</flux:heading>
        @php
            $adverts = SellAdvert::query()
            ->where('user_id', '!=', auth()->id())
            ->where('is_published', true)
            ->orderBy('remaining_balance', 'desc')
            ->orderBy('available_balance', 'desc')
            ->with(['user.wallet'])
            ->paginate();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($adverts as $advert)
                <x-adverts.gigs :advert="$advert" wire:click="viewAdvert({{ $advert->id }})" />
            @endforeach
        </div>
    </div>

    <div class="my-12">
        {{ $adverts->links() }}
    </div>
</div>
