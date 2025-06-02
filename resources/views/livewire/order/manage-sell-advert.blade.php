<?php

use Livewire\Volt\Component;
use App\Models\SellAdvert;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateSellAdvert;
use Livewire\Attributes\Validate;
use App\Actions\UpdateSellOrder;
use App\Actions\UpdateSellOrderAvailableFunds;
use App\Enums\SellAdvertType;
use App\Enums\SellAdvertStatus;

new class extends Component {
    use WithPagination;

    public SellAdvert $sellAdvert;

    public float $currentRate;

    public $funding_type = '';

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

        $this->min_amount = $this->sellAdvert->minimum_sell;
        $this->max_amount = $this->sellAdvert->max_sell;
        $this->terms = $this->sellAdvert->terms;
        $this->payment_method = $this->sellAdvert->payment_method ?? '';
        $this->is_usdt = $this->sellAdvert->type === SellAdvertType::Usdt;
        $this->network = $this->sellAdvert->network_type ?? '';
        $this->wallet_address = $this->sellAdvert->wallet_address ?? '';

        $this->currentRate = 1200;
    }

    public function editAdvert()
    {
        Flux::modal('edit-modal')->show();
    }

    public function updateAmount(UpdateSellOrderAvailableFunds $availableFunds)
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'funding_type' => 'required|in:add,remove'
        ], [
            'funding_type.required' => 'Please select one of the funding actions.'
        ]);

        Flux::modal('edit-amount-modal')->close();

        try {
            $user = Auth::user();

            $this->sellAdvert = $availableFunds->execute($this->sellAdvert, [
                'funding_type' => $this->funding_type,
                'amount' => $this->amount,
            ]);

            $this->dispatch("flash-success", message: "Your sell order has been updated.");
        } catch (Exception $ex) {
            report($ex);
            $this->dispatch("flash-error", message: $ex->getMessage());
        }
    }

    public function update(UpdateSellOrder $updateSellOrder)
    {
        $this->validate();

        Flux::modal('edit-modal')->close();

        try {
            $user = Auth::user();

            $this->sellAdvert = $updateSellOrder->execute($this->sellAdvert, [
                'min_amount' => $this->min_amount,
                'max_amount' => $this->max_amount,
                'terms' => $this->terms,
                'payment_method' => $this->payment_method,
                'is_usdt' => $this->is_usdt,
                'network' => $this->network,
                'wallet_address' => $this->wallet_address
            ]);

            $this->dispatch("flash-success", message: "Your sell order has been updated");
        } catch (Exception $ex) {
            report($ex);
            $this->dispatch("flash-error", message: $ex->getMessage());
        }
    }

    public function toggleAvailability()
    {
        $newState = !$this->sellAdvert->is_published;

        $this->sellAdvert->update(['is_published' => $newState]);

        $this->dispatch("flash-success", message: "Order status updated");
    }

    public function toggleSaleStatus()
    {
        $newState = match ($this->sellAdvert->status) {
            \App\Enums\SellAdvertStatus::Available => \App\Enums\SellAdvertStatus::SoldOut,
            \App\Enums\SellAdvertStatus::SoldOut => \App\Enums\SellAdvertStatus::Available,
        };

        $this->sellAdvert->update(['status' => $newState]);

        $this->dispatch("flash-success", message: "Order availability status updated");
    }
}; ?>

<div>
    <flux:modal name="edit-modal" class="w-full md:max-w-screen-sm" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Sell Order</flux:heading>
                <flux:subheading>Sell to users on profitchain</flux:subheading>
            </div>

            <form wire:submit="update" class="space-y-6 mt-12">
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

                    <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Update Order Details</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="edit-amount-modal" class="w-full md:max-w-screen-sm" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add/Remove Funds</flux:heading>
                <flux:subheading>You can add more funds to the sell order to enable you serve more buyers.</flux:subheading>
            </div>

            <form wire:submit="updateAmount" class="space-y-6 mt-12">

                <flux:radio.group wire:model="funding_type" name="funding_type" label="Select your preferred action" required>
                    <flux:radio value="add" label="Add Funds" />
                    <flux:radio value="remove" label="Remove Funds" />
                </flux:radio.group>

                <flux:input
                    type="number"
                    step="0.01"
                    name="amount"
                    wire:model="amount"
                    label="Amount($)"
                    placeholder="Amount($)"
                    required
                />

                <div class="flex">
                    <flux:spacer />

                    <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Update Order Details</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    @if(!$sellAdvert)
        <div class="border rounded-lg flex flex-col items-center justify-center py-12 gap-4">
            <flux:icon name="columns-4" class="size-12 text-gray-400" />
            <flux:heading>Start taking orders for USD Sale right now.</flux:heading>

            <a href="{{ route('sell.create-sell-order') }}" wire:navigate>
                <flux:button icon="plus" variant="primary" class="cursor-pointer">Create Sell Order</flux:button>
            </a>
        </div>
    @endif

    <div class="border rounded-lg w-full flex flex-col gap-3 p-3 xl:flex-row">
        <div>
            <div class="relative z-10 flex flex-1 flex-col items-center gap-5 overflow-hidden rounded-[0.6rem] bg-gradient-to-b from-theme-2/90 to-theme-1/[0.85] px-10 py-12 before:absolute before:left-0 before:top-0 before:-ml-[35%] before:hidden before:h-[130%] before:w-full before:-rotate-[38deg] before:bg-gradient-to-b before:from-black/[0.08] before:to-transparent before:content-[''] lg:flex-row xl:w-[300px] xl:flex-col xl:items-start xl:gap-14 xl:py-9 before:xl:block">
                <div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full border border-accent border-2 bg-accent/20">
                        <flux:icon
                            class="h-6 w-6 text-accent"
                            name="wallet-cards"
                        />
                    </div>
                </div>
                <div>
                    <flux:heading size="lg" class="text-center text-base text-white lg:text-left">
                        Total Available
                    </flux:heading>
                    <div class="mt-2 flex items-center justify-center lg:justify-start">
                        <flux:heading size="xl" class="text-2xl font-medium text-white">
                            {{ to_money($sellAdvert->available_balance, 100, '$') }}
                        </flux:heading>
                    </div>
                </div>
                <div class="lg:ml-auto xl:ml-0 xl:w-full">

                    <flux:badge class="py-2 px-4 w-full justify-center" color="{{ $sellAdvert->type->getFluxColor() }}">{{ $sellAdvert->type->getLabel() }} Enabled</flux:badge>
                    <div class="h-4"></div>

                    <div class="flex gap-2">
                        <flux:badge class="py-2 px-4" color="{{ $sellAdvert->status->getFluxColor() }}">{{ $sellAdvert->status->getLabel() }}</flux:badge>
                        <flux:button wire:click="toggleSaleStatus" variant="ghost" class="cursor-pointer underline">Mark as {{ $sellAdvert->status->getAlternateLabel() }}</flux:button>
                    </div>
                    <div class="h-4"></div>

                    <div class="flex gap-2">
                        <flux:badge class="py-2 px-4" color="{{ $sellAdvert->is_published ? 'green' : 'red' }}">{{ $sellAdvert->is_published ? 'Published' : 'Not published' }}</flux:badge>
                        <flux:button wire:click="toggleAvailability" variant="ghost" class="cursor-pointer underline">{{ $sellAdvert->is_published ? 'Unpublished' : 'Published' }} order</flux:button>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <flux:modal.trigger name="edit-modal">
                            <flux:button icon="plus" variant="primary" class="cursor-pointer w-full">Edit order details</flux:button>
                        </flux:modal.trigger>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <flux:modal.trigger name="edit-amount-modal">
                            <flux:button icon="plus" variant="primary" class="cursor-pointer w-full">Add/Remove Funds</flux:button>
                        </flux:modal.trigger>
                    </div>

                </div>
            </div>
        </div>

        <div class="flex w-full flex-col rounded-[0.6rem] border border-dashed border-slate-300/80 p-5 sm:px-8 sm:py-7">

            <div class="mt-6 flex flex-1 flex-col gap-y-5 sm:mb-4 sm:mt-8 md:flex-row lg:mt-6 xl:mb-0">
                <div class="grid grid-cols-2 gap-5 md:-mb-4 md:-mt-2 xl:gap-0">

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <div class="text-base">
                                <flux:badge :color="$sellAdvert->is_published ? 'green' : 'red'">
                                    {{ $sellAdvert->is_published ? 'Published' : 'Not Published' }}
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Status
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <div class="text-base">
                                <flux:badge :color="$sellAdvert->status->getFluxColor()">
                                    {{ $sellAdvert->status->getLabel() }}
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Availability Status
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ to_money($sellAdvert->available_balance, 100, '$') }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Available Amount
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ to_money($sellAdvert->remaining_balance, 100, '$') }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Total USD
                            </flux:heading>
                        </div>
                    </div>

                </div>
                <div class="grid grid-cols-2 gap-5 md:mx-auto md:-mb-4 md:-mt-2 xl:gap-0">

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ to_money($sellAdvert->minimum_sell) }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Minimum Amount
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ to_money($sellAdvert->max_sell) }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Maximum Amount
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ to_money($currentRate) }}/USD</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Rate
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ $sellAdvert->orders_count ?? 0 }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Total Orders
                            </flux:heading>
                        </div>
                    </div>

                </div>
                <div class="grid grid-cols-2 gap-5 md:-mb-4 md:-mt-2 xl:gap-0">

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ $sellAdvert->bank_name }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Bank Name
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ $sellAdvert->bank_account_name }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Account Name
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base">{{ $sellAdvert->bank_account_number }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Account Number
                            </flux:heading>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-1 flex-col justify-center sm:col-span-1 md:col-span-2">
                        <div class="mb-1.5 flex items-center">
                            <flux:subheading class="text-base line-clamp-[1]" title="{{ $sellAdvert->terms }}">{{ $sellAdvert->terms }}</flux:subheading>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <flux:heading class="truncate sm:max-w-[9rem]">
                                Sale Terms
                            </flux:heading>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
