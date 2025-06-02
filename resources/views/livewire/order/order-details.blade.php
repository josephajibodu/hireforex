<?php

use Livewire\Volt\Component;
use App\Models\SellAdvert;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateBuyOrder;
use App\Models\Order;
use App\Actions\CancelBuyOrder;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

new class extends Component {
    use WithPagination;
    use WithFileUploads;

    public Order $order;

    #[Validate('image|max:3072')]
    public $payment_proof;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function updatedAmount()
    {
        $this->amountToPay = floatval($this->amount ?? 0) * $this->sellAdvert->unit_price;
    }

    public function viewAdvert(int $advertId)
    {
        $this->sellAdvert = SellAdvert::query()->findOrFail($advertId);

        Flux::modal('confirm')->show();
    }

    public function cancelOrder(CancelBuyOrder $cancelBuyOrder)
    {
        Flux::modal('cancel-order-modal')->close();

        try {
            $user = Auth::user();

            $cancelBuyOrder->execute($this->order);

            $this->dispatch('flash-success', message: "Your buy order has been cancelled" );
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch('flash-error', title: 'Failed to cancel buy order', message: $ex->getMessage());
        }
    }

    public function savePaymentProof()
    {
        if (! $this->payment_proof) {
            $this->dispatch(
                'flash-info',
                title: 'Unsuccessful!',
                message: 'Please wait for the upload to complete before clicking submit. Try again'
            );
            return;
        }

        $path = $this->payment_proof->store(path: 'payment_proofs');

        $this->order->update([
            'payment_proof' => $path,
            'status' => \App\Enums\OrderStatus::Paid
        ]);

        $this->dispatch(
            'flash-success',
            title: 'The dealer has been notified',
            message: 'Your payment will be confirmed within 1hour by the dealer and your USD will be credited into your main balance.'
        );
    }
}; ?>

<div>
    <flux:modal name="confirm" class="w-full md:max-w-screen-sm" variant="flyout">
        <div class="space-y-6">

        </div>
    </flux:modal>

    <!-- Details -->
    <div class="grid grid-cols-12 gap-x-6 gap-y-10 text-sm">
        <div class="col-span-12 flex flex-col gap-y-10 xl:col-span-4">
            <div class="border rounded-lg p-5">
                <div class="mb-5 border-b border-dashed pb-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="flex items-center">
                            <flux:heading class="text-accent">
                                Buy {{ to_money($order->coin_amount, 100, hideSymbol: true) }} USD
                            </flux:heading>
                        </div>

                        <div class="flex items-center rounded-full font-medium sm:ml-auto">
                            <flux:badge class="flex px-3 py-1 font-medium rounded-full"
                                        color="{{ $order->status->getFluxColor() }}">
                                {{ $order->status->getLabel() }}
                            </flux:badge>
                        </div>

                    </div>
                </div>

                @if(! $order->inCompletedState())
                    <div class="py-4 mb-4 flex justify-between border-b border-dashed">
                        <div class="text-slate-500">Time Limit</div>
                        <x-countdown-timer :time-remaining="$order->getTimeLeft()" message="Time elapsed"/>
                    </div>
                @endif

                <div>
                    <flux:heading>Dealer Rate</flux:heading>
                    <div class="mt-1 flex items-center">
                        <flux:subheading class="font-medium">{{ to_money($order->seller_unit_price) }}</flux:subheading>
                    </div>
                </div>

                @if(! $order->inCompletedState())
                    <div class="mt-4 flex flex-col rounded-[0.6rem] border border-dashed border-slate-300/80">
                        <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                            <div>
                                <div class="flex items-center whitespace-nowrap text-slate-500 gap-1">
                                    Min
                                    <flux:tooltip content="Minimum Buy">
                                        <flux:icon class="size-4" name="information-circle" icon-variant="outline"/>
                                    </flux:tooltip>
                                </div>
                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                    {{ to_money($order->sellAdvert->minimum_sell) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                            <div>
                                <div class="flex items-center whitespace-nowrap text-slate-500 gap-1">
                                    Max
                                    <flux:tooltip content="Maximum Buy">
                                        <flux:icon class="size-4" name="information-circle" icon-variant="outline"/>
                                    </flux:tooltip>
                                </div>
                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                    {{ to_money($order->sellAdvert->max_sell) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6 font-medium">Dealer Details</div>
                <div class="mt-4 flex flex-col rounded-[0.6rem] border border-dashed border-slate-300/80">
                    <div
                            class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Full Name
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->sellAdvert->user->full_name }}
                            </div>
                        </div>
                    </div>
                    <div
                            class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Username
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->sellAdvert->user->username }}
                            </div>
                        </div>
                    </div>
                    <div
                            class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Whatsapp Number
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->sellAdvert->user->whatsapp_number }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                        <div>
                            <div class="flex items-center whitespace-nowrap text-slate-500">
                                Created
                            </div>
                            <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>


                    @if($order->inCompletedState())
                        <div class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                            <div>
                                <div class="flex items-center whitespace-nowrap text-slate-500">
                                    Last updated
                                </div>
                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                    {{ $order->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Cancel the order: Start -->
                @if($order->status === \App\Enums\OrderStatus::Pending)
                    <flux:modal.trigger name="cancel-order-modal">
                        <flux:button variant="danger" class="mt-4">Cancel Order</flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="cancel-order-modal" class="min-w-[22rem]">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Cancel Order?</flux:heading>

                                <flux:subheading>
                                    <p>You're about to cancel this order.</p>
                                    <p>This action cannot be reversed.</p>
                                </flux:subheading>
                            </div>

                            <div class="flex gap-2">
                                <flux:spacer/>

                                <flux:modal.close>
                                    <flux:button variant="ghost">Cancel</flux:button>
                                </flux:modal.close>

                                <flux:button wire:click="cancelOrder" variant="danger">Cancel Order</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endif
                <!-- Cancel the order: End -->

            </div>
        </div>

        <div class="col-span-12 flex flex-col gap-y-10 xl:col-span-8">
            <div class="rounded-lg border">
                @if($order->status === \App\Enums\OrderStatus::Completed)
                    <div class="box box--stacked mt-3.5 p-5">
                        <flux:heading size="lg" class="my-4 font-medium">
                            Check your <a href="{{ route('buy.history') }}" wire:navigate class="text-primary underline">ORDER
                                LIST</a> to view your orders
                        </flux:heading>

                        <div role="alert"
                             class="alert relative border rounded-md px-5 py-4 bg-green-50 border-green-500 text-green-600 dark:border-green-500 mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6 me-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            Order Completed. You funds have been released to your wallet.
                        </div>
                    </div>
                @endif

                @if($order->status === \App\Enums\OrderStatus::Cancelled)
                    <div class="box box--stacked mt-3.5 p-5">
                        <flux:heading size="lg" class="my-4 font-medium">
                            Check your <a href="{{ route('buy.history') }}" wire:navigate class="text-primary underline">ORDER
                                LIST</a> to view your orders
                        </flux:heading>

                        <div role="alert"
                             class="alert relative border rounded-md px-5 py-4 bg-red-50 text-red-500 border-red-500 dark:border-danger mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6 me-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            Order Cancelled
                        </div>
                    </div>
                @endif

                @if($order->inPendingState())
                    <div class="box box--stacked p-5">

                        @if($order->status === \App\Enums\OrderStatus::PaymentNotReceived)
                            <div class="box box--stacked mb-4">
                                <div role="alert"
                                     class="alert relative border rounded-md px-5 py-4 bg-red-50 border-red-500 text-red-500 dark:border-red-500 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-6 me-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    Dealer said <b>&nbsp;Payment Not Received</b>. Contact Dealer Via WhatsApp
                                </div>
                            </div>
                        @endif

                        <div class="mb-5 border-b border-dashed pb-5">
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center">
                                    <div class="">
                                        <div class="relative">
                                            <flux:heading size="lg">Dealer's terms</flux:heading>
                                        </div>
                                        <div class="mt-0.5 text-sm text-slate-500">
                                            {{ $order->sellAdvert->terms }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center border-t pt-4">
                                    <div class="">
                                        <div class="relative">
                                            <flux:heading size="lg">Payment Method</flux:heading>
                                        </div>
                                        <div class="mt-0.5 text-sm text-slate-500">
                                            {{ $order->sellAdvert->payment_method ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($order->status === \App\Enums\OrderStatus::Pending)
                            <div>
                                @if($order->type === \App\Enums\SellAdvertType::Usdt)
                                    <flux:heading size="lg" class="mt-6 font-medium">Transfer {{ to_money($order->coin_amount, 100, false) }} USDT to the Dealer wallet address provided below.</flux:heading>

                                    <div class="mt-4 flex flex-col rounded-[0.6rem] border border-dashed border-slate-300/80">
                                        <div
                                                class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                            <div>
                                                <div class="flex items-center whitespace-nowrap text-slate-500">
                                                    Dealer Network Type
                                                </div>
                                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                                    {{ $order->network_type ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                                class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                            <div>
                                                <div class="flex items-center whitespace-nowrap text-slate-500" x-data="{ copied: false }">
                                                    <span class="me-4">Dealer Wallet Address</span>

                                                    <flux:button
                                                        icon="clipboard-document"
                                                        class="cursor-pointer"
                                                        size="xs"
                                                        variant="primary"
                                                        @click="navigator.clipboard.writeText(`{{ $order->wallet_address ?? '-' }}`).then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                                    >Copy Address</flux:button>
                                                    <flux:heading x-show="copied" class="text-green-500! text-xs ml-2">Copied!</flux:heading>
                                                </div>
                                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                                    {{ $order->wallet_address ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <flux:heading size="lg" class="mt-6 font-medium">After sending the USDT to the wallet address, upload screenshot and click on
                                        “I have made payment, Notify Dealer” button.
                                    </flux:heading>
                                @else
                                    <flux:heading size="lg" class="mt-6 font-medium">Transfer {{ to_money($order->total_amount) }}
                                        to the Dealer account provided below.
                                    </flux:heading>

                                    <div class="mt-4 flex flex-col rounded-[0.6rem] border border-dashed border-slate-300/80">
                                        <div
                                                class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                            <div>
                                                <div class="flex items-center whitespace-nowrap text-slate-500">
                                                    Dealer Bank Name
                                                </div>
                                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                                    {{ $order->sellAdvert->bank_name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                                class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                            <div>
                                                <div class="flex items-center whitespace-nowrap text-slate-500">
                                                    Dealer Account Name
                                                </div>
                                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                                    {{ $order->sellAdvert->bank_account_name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                                class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                            <div>
                                                <div class="flex items-center whitespace-nowrap text-slate-500">
                                                    Dealer Account Number
                                                </div>
                                                <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                                    {{ $order->sellAdvert->bank_account_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <flux:heading size="lg" class="mt-6 font-medium">After transferring the amount, upload screenshot and click on
                                        “I have made payment, Notify Dealer” button.
                                    </flux:heading>
                                @endif

                                <form wire:submit="savePaymentProof" class="mt-4">
                                    <flux:input
                                        required="true"
                                        type="file"
                                        accept="image/*"
                                        wire:model="payment_proof"
                                        label="Payment proof"
                                    />

                                    <flux:button type="submit" class="mt-4" variant="primary" wire:loading.attr="disabled">I have made payment, Notify Dealer</flux:button>
                                </form>
                            </div>
                        @endif

                        @if($order->status === \App\Enums\OrderStatus::Paid)
                            <div>
                                <flux:heading class="mb-4" size="lg">Uploaded Payment Proof</flux:heading>
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($order->payment_proof) }}"
                                    alt="payment proof"
                                    class="aspect-[3/4] w-24 object-cover border rounded-lg mb-4"
                                />
                            </div>

                            <div role="alert"
                                 class="alert relative border rounded-md px-5 py-4 bg-sky-50 border-sky-500 text-sky-500 dark:border-sky-500 mb-2 flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-6 me-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <flux:subheading class="mb-0 text-sky-500!" size="lg">Please hold on while the Dealer confirms your payment
                                    and release your {{ to_money($order->coin_amount, 100, '$') }} into your wallet.
                                </flux:subheading>
                            </div>
                        @endif


                        <flux:heading size="lg" class="mt-12 font-medium">
                            Check your <a href="{{ route('buy.history') }}" wire:navigate class="text-primary underline">ORDER
                                LIST</a> to view your transaction status or contact the dealer with the
                            below details;
                        </flux:heading>

                        <div class="mt-4 flex flex-col rounded-[0.6rem] border border-dashed border-slate-300/80">
                            <div
                                    class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                <div>
                                    <div class="flex items-center whitespace-nowrap text-slate-500">
                                        Whatsapp Number
                                    </div>
                                    <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                        {{ $order->sellAdvert->user->whatsapp_number }}
                                    </div>
                                </div>
                            </div>
                            <div
                                    class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                <div>
                                    <div class="flex items-center whitespace-nowrap text-slate-500">
                                        Phone Number
                                    </div>
                                    <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                        {{ $order->sellAdvert->user->phone_number }}
                                    </div>
                                </div>
                            </div>
                            <div
                                    class="flex items-center border-b border-dashed border-slate-300/80 px-3.5 py-2.5 last:border-0">
                                <div>
                                    <div class="flex items-center whitespace-nowrap text-slate-500">
                                        Email Address
                                    </div>
                                    <div class="mt-1 whitespace-nowrap font-medium text-slate-600">
                                        {{ $order->sellAdvert->user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
