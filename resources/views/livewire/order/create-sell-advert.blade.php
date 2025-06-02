<?php

use Livewire\Volt\Component;
use App\Models\SellAdvert;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateSellAdvert;
use Livewire\Attributes\Validate;

new class extends Component {
    use WithPagination;

    public ?SellAdvert $sellAdvert;

    #[Validate('required|numeric|min:1')]
    public $amount = '';

    #[Validate('required|numeric')]
    public $min_amount = '';

    #[Validate('required|numeric')]
    public $max_amount = '';

    #[Validate('nullable|string')]
    public string $terms = '';

    #[Validate('string')]
    public string $payment_method = '';

    #[Validate('boolean')]
    public bool $is_usdt = false;

    #[Validate('nullable|string')]
    public string $wallet_address = '';

    #[Validate('nullable|string')]
    public string $network = '';

    public function mount()
    {
        $this->sellAdvert = Auth::user()->sellAdvert;
    }

    public function editAdvert(int $advertId)
    {
        Flux::modal('create-modal')->show();
    }

    public function create(CreateSellAdvert $createSellAdvert)
    {
        $this->validate();

        Flux::modal('create-modal')->close();

        try {
            $user = Auth::user();

            $this->sellAdvert = $createSellAdvert->execute($user, [
                'amount' => $this->amount,
                'min_amount' => $this->min_amount,
                'max_amount' => $this->max_amount,
                'terms' => $this->terms,
                'payment_method' => $this->payment_method,
                'is_usdt' => $this->is_usdt,
                'network' => $this->network,
                'wallet_address' => $this->wallet_address
            ]);

            $this->dispatch("flash-success", message: "Your sell order has been published to the marketplace.");
        } catch (Exception $ex) {
            report($ex);
            $this->dispatch("flash-error", message: $ex->getMessage());
        }
    }
}; ?>

<div>
    <flux:modal name="create-modal" class="w-full md:max-w-screen-sm" variant="flyout"  >
        <div class="space-y-6">
            <div>
                <flux:heading class="text-xl! md:text-2xl!">Create Sell Order</flux:heading>
                <flux:subheading>Sell to users on profitchain</flux:subheading>
            </div>

            <form wire:submit="create" class="space-y-6 mt-12">
                <flux:input
                    type="number"
                    step="0.01"
                    name="amount"
                    wire:model="amount"
                    label="Amount(USD)"
                    placeholder="Amount(USD) to Sell"
                    required
                />
                <flux:input
                    type="number"
                    step="0.01"
                    name="min_amount"
                    wire:model="min_amount"
                    label="Min Amount(₦)"
                    placeholder="Minimum Amount(₦)"
                    required
                />

                <flux:input
                    type="number"
                    step="0.01"
                    name="max_amount"
                    wire:model="max_amount"
                    label="Max Amount(₦)"
                    placeholder="Maximum Amount(₦)"
                    required
                />

                <flux:switch
                    align="left"
                    name="is_usdt"
                    wire:model.live="is_usdt"
                    label="Accept Payment via USDT"
                />

                @if(! $is_usdt)
                    <flux:input
                        type="text"
                        name="payment_method"
                        wire:model="payment_method"
                        label="Payment Method"
                        placeholder="e.g Bank Transfer, Palmpay only"
                        required
                    />
                @endif

                @if($is_usdt)
                    <flux:select
                        name="network"
                        wire:model.live.debounce="network"
                        required="true"
                        variant="default"
                        label="Network Type"
                    >
                        <flux:select.option>TRC-20</flux:select.option>
                        <flux:select.option>BEP-20</flux:select.option>
                        <flux:select.option>ERC-20</flux:select.option>
                    </flux:select>

                    <flux:input
                        name="wallet_address"
                        wire:model.live.debounce="wallet_address"
                        placeholder="Wallet Address"
                        type="text"
                        required="true"
                        label="Wallet Address"
                    />
                @endif

                <flux:textarea
                    rows="10"
                    name="terms"
                    wire:model="terms"
                    label="Sale's Term"
                    placeholder="Enter your sale terms"
                    required
                />

                <div class="flex">
                    <flux:spacer />

                    <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Publish Order</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    @if(! $sellAdvert)
        <div class="border rounded-lg flex flex-col items-center justify-center py-12 gap-4">
            <flux:icon name="columns-4" class="size-12 text-gray-400" />
            <flux:heading>Start taking orders for USD Sale right now.</flux:heading>

            <flux:modal.trigger name="create-modal">
                <flux:button icon="plus" variant="primary" class="cursor-pointer">Create Sell Order</flux:button>
            </flux:modal.trigger>
        </div>
    @else
        <div class="border rounded-lg flex flex-col items-center justify-center py-12 gap-4 mt-8 bg-accent">
            <flux:icon name="package-check" class="size-12 text-white" />
            <flux:heading class="text-white!">Your sell order is active and ready.</flux:heading>

            <a href="{{ route('sell.history') }}" wire:navigate>
                <flux:button icon="eye" class="cursor-pointer">View Sell Order</flux:button>
            </a>
        </div>
    @endif
</div>
