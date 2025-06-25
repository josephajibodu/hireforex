<?php

use Livewire\Volt\Component;
use App\Models\GiftCard;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateGiftCardOrder;
use Livewire\Attributes\Validate;

new class extends Component {
    use WithPagination;

    public ?int $limit = null;
    public GiftCard $selected_gift_card;

    #[Validate('required|numeric|min:1')]
    public int $quantity = 1;

    public float $total_amount = 0;

    public bool $showHeader;

    public bool $guest;

    public function mount($limit = null, $showHeader = true, $guest = false)
    {
        $this->limit = $limit;
        $this->showHeader = $showHeader;
        $this->guest = $guest;
    }

    public function updatedQuantity()
    {
        if ($this->selected_gift_card) {
            $this->total_amount = floatval($this->quantity ?? 0) * $this->selected_gift_card->amount;
        }
    }

    public function purchaseGiftCard(int $giftCardId)
    {
        $this->selected_gift_card = GiftCard::query()->findOrFail($giftCardId);

        $this->total_amount = $this->selected_gift_card->amount;

        if (!$this->selected_gift_card->is_available || $this->selected_gift_card->available->count() < 1) {
            $this->dispatch(
                "flash-info",
                message: "The selected gift card is currently not available, please check back when the minimum available is 1 or more"
            );
            return;
        }

        Flux::modal('confirm')->show();
    }

    public function create(CreateGiftCardOrder $createGiftCardOrder)
    {
        $this->validate();

        Flux::modal('confirm')->close();

        if (!$this->quantity || !$this->selected_gift_card) {
            $this->dispatch(
                "flash-info",
                message: "Please select a valid gift card and enter a valid quantity"
            );
            return;
        }

        try {
            $user = Auth::user();

            // Check if user has sufficient balance
            if (! $user->hasSufficientBalance($this->total_amount)) {
                $this->dispatch(
                    "flash-error",
                    message: "You currently don't have enough funds in your Cardbeta wallet. Please top up your balance to proceed with purchasing gift cards."
                );
                return;
            }

            // Check if requested quantity is available
            if ($this->selected_gift_card->available->count() < $this->quantity) {
                $this->dispatch(
                    "flash-error",
                    message: "Only {$this->selected_gift_card->available->count()} units of this gift card are available."
                );
                return;
            }

            $order = $createGiftCardOrder->execute($user, $this->selected_gift_card, $this->quantity);
            $costAmount = to_money($order->getExpectedResaleValue(), currency: '$');

            $this->dispatch(
                "flash-success",
                message: "Your gift card order has been successfully created! Cardbeta will process the purchase and resale automatically. You'll receive $costAmount in your Cardbeta balance after the delivery period. Check your Current Orders section to track the progress."
            );

            $this->reset(['quantity', 'total_amount', 'selected_gift_card']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch("flash-error", message: $ex->getMessage() ?? 'Failed to create gift card order');
            $this->reset(['quantity', 'total_amount', 'selected_gift_card']);
        }
    }
}; ?>

<div>
    <flux:modal name="confirm" class="w-full md:max-w-screen-sm" variant="flyout">
        <div class="space-y-6">
            @if(!$selected_gift_card)
                <div>
                    <flux:heading size="lg">Gift Card Purchase</flux:heading>
                    <flux:subheading></flux:subheading>
                </div>
            @else
                <div>
                    <flux:heading size="lg">{{ $selected_gift_card->name }}</flux:heading>
                    <flux:subheading>Purchase gift cards at ${{ number_format($selected_gift_card->denomination, 2) }} each.</flux:subheading>
                </div>

                <flux:input
                    wire:model.live.debounce.1000="quantity"
                    type="number"
                    min="1"
                    max="{{ $selected_gift_card->available->count() }}"
                    label="Quantity"
                    placeholder="Number of gift cards to purchase"
                />

                <div class="border p-4 rounded-lg">
                    <flux:subheading>Total Amount</flux:subheading>
                    <flux:heading size="xl">{{ to_money($total_amount, hideSymbol: true) }} USDT</flux:heading>
                </div>

                <div class="flex">
                    <flux:spacer />
                    <flux:button variant="primary" wire:loading.attr="disabled" wire:click="create">Proceed to Purchase</flux:button>
                </div>
            @endif
        </div>
    </flux:modal>

    <!-- Available Gift Cards -->
    <div class="mt-8">
        <div class="flex flex-col gap-2 mb-2">
            <flux:heading class="text-xl! md:text-2xl!">Available Gift Cards</flux:heading>
            @if($showHeader)
                <div class="flex gap-2 justify-between">
                    <flux:button icon-trailing="shopping-cart" href="{{ route('marketplace.active_orders') }}">
                        My Orders
                    </flux:button>
                    <flux:button icon-trailing="history" href="{{ route('marketplace.all_orders') }}">
                        Order History
                    </flux:button>
                </div>
            @endif
        </div>
        @php
            $giftCards = GiftCard::query()
                ->where('is_available', true);

            if ($limit) {
                $giftCards = $giftCards->limit($limit)->get();
            } else {
                $giftCards = $giftCards->paginate(10);
            }
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse($giftCards as $giftCard)
                <x-main.giftcard :giftCard="$giftCard" :redirect="$guest" />
            @empty
                <div class="flex flex-col justify-center items-center col-span-full py-12">
                    <flux:icon name="gift" class="size-12 text-gray-300 mb-4" />
                    <flux:subheading size="lg">There are currently no gift cards available</flux:subheading>
                </div>
            @endforelse
        </div>
    </div>

    @if(!$limit)
        <div class="my-4">
            {{ $giftCards->links() }}
        </div>
    @endif
</div>
