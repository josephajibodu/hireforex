<?php

use Livewire\Volt\Component;
use App\Actions\InitiateTransfer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use App\Models\User;
use Livewire\WithFileUploads;
use App\Enums\OrderDisputeStatus;
use App\Models\OrderDispute;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:1000')]
    public $description = '';

    #[Validate('image|max:2048')]
    public $proofs = '';

    public User $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function create(InitiateTransfer $initiateTransfer)
    {
        $this->validate();

        try {

            OrderDispute::query()->create([
                'description' => $this->description,
                'proofs' => $this->proofs->store('dispute_proofs'),
                'user_id' => Auth::id(),
                'status' => OrderDisputeStatus::Open
            ]);

            $this->dispatch('flash-success',
                title: "Dispute submitted",
                message: "Your dispute has been submitted successfully and you will be
contacted by an approved mediator on the progress of the issue, and we hope to get it
resolved as soon as possible."
            );

            $this->reset(['description', 'proofs']);
        } catch (Exception $ex) {
            report($ex);

            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'Dispute could not be submitted');
        }
    }
}; ?>

<div>
    <div class="mb-8">
        <flux:heading class="text-xl! md:text-2xl!">Dispute Form</flux:heading>
    </div>

    <form wire:submit="create">

        <flux:textarea
            rows="10"
            name="description"
            wire:model="description"
            label="What is the issue (include the dealer information also)"
            placeholder="Maximum of 1000 words"
            required="true"
            class="mb-6"
        />

        <flux:input
            required="true"
            type="file"
            accept="image/*"
            name="proofs"
            wire:model="proofs"
            label="Upload file (maximum 2mb)"
        />

        <div class="flex mt-8">
            <flux:spacer/>

            <flux:button variant="primary" wire:loading.attr="disabled" type="submit">Submit Dispute</flux:button>
        </div>
    </form>
</div>
