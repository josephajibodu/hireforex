<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use App\Models\User;
use Livewire\WithFileUploads;
use App\Models\KycVerification;
use App\Enums\KYCStatus;
use Flux\Flux;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:1000')]
    public $document_type = '';

    #[Validate('required|image|max:2048')]
    public $document = '';

    public User $user;

    public ?KycVerification $kyc;

    public function mount()
    {
        $this->user = Auth::user();

        $this->kyc = $this->user->kyc;
    }

    public function create()
    {
        $this->validate();

        Flux::modal('kyc-form')->close();

        try {
            $path = $this->document->store('kyc');

            $this->kyc = KycVerification::query()->updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'document' => $path,
                    'document_type' => $this->document_type,
                    'status' => KYCStatus::Pending
                ]
            );

            $this->dispatch('flash-success',
                title: "Document submitted",
                message: "Your ID has been uploaded successfully, it will be reviewed, check your dashboard within the next 1 hour."
            );

            $this->reset(['document', 'document_type']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'Dispute could not be submitted');
        }
    }
}; ?>

<div class="">
    @if(! $kyc || ($kyc && $kyc->status !== KYCStatus::Completed))
        <div class="relative flex overflow-hidden rounded-xl border border-accent bg-accent/10 px-4 py-4">
            <div class="flex flex-wrap items-center">
                @if($kyc && $kyc->status === KYCStatus::Pending)
                    <flux:heading class="text-accent!">
                        Your KYC verification is in progress. Please be patient while we review your submitted document. The process should take no longer than 1 hour.
                    </flux:heading>
                @else
                    <flux:heading class="text-accent!">Your account is not yet verified, Please complete your KYC verification today.</flux:heading>
                    <flux:modal.trigger name="kyc-form">
                        <flux:button variant="ghost" class="underline text-accent!">Verify Now!</flux:button>
                    </flux:modal.trigger>
                @endif
            </div>

            <div class="bg-red-500 w-36 h-full"></div>
            <img src="{{ asset('images/customer.png') }}" class="w-36 sm:w-28 absolute -top-4 -right-12 sm:right-0 rotate-[-30deg]" alt="kyc" />
        </div>


        @if($kyc && $kyc->status === KYCStatus::Rejected)
            <div class="text-center my-2 flex justify-center items-center gap-2">
                <flux:icon name="circle-x" class="size-5 text-red-500" />
                <flux:heading>Identity Verification Failed: <span class="text-gray-500">{{ $kyc->comment }}</span></flux:heading>
            </div>
        @endif

        @if($kyc && $kyc->status === KYCStatus::Pending)
            <div class="text-center my-2 flex justify-center items-center gap-2">
                <flux:icon name="circle-dot" class="size-5 text-amber-500 animate-pulse" />
                <flux:heading>Identity Verification in Progress.</flux:heading>
            </div>
        @endif

        <flux:modal name="kyc-form" class="">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">KYC Verification</flux:heading>
                <flux:subheading>Please upload a valid identity document</flux:subheading>
            </div>

            <form wire:submit="create" class="space-y-8">

                <flux:select
                    name="document_type"
                    wire:model="document_type"
                    placeholder="Select Identity Document"
                    label="Identity Document"
                >
                    @foreach(\App\Enums\KYCDocumentType::cases() as $kycDocType)
                        <flux:select.option value="{{ $kycDocType->value }}">{{ $kycDocType->getLabel() }}</flux:select.option>
                    @endforeach
                </flux:select>


                <flux:input
{{--                    required="true"--}}
                    type="file"
                    accept="image/*"
                    name="document"
                    wire:model="document"
                    label="Upload A Means of Identification (maximum 2mb)"
                />

                <div class="flex mt-8">
                    <flux:spacer/>

                    <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Submit Document</flux:button>
                </div>
            </form>

        </div>
    </flux:modal>

    @endif
</div>
