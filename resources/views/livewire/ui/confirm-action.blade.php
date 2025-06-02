<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?string $action = null;
    public array $params = [];
    public string $message = 'Are you sure?';

    #[On('showConfirmModal')]
    public function showConfirmModal(string $action, array $params = [], string $message = 'Are you sure?')
    {
        $this->action = $action;
        $this->params = $params;
        $this->message = $message;
    }

    public function confirm()
    {
        if ($this->action) {
            $this->dispatch($this->action, ...$this->params);
        }
        $this->reset(['action', 'params', 'message']);
    }
}; ?>

<div>
    <flux:modal name="confirm-action-modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirmation</flux:heading>

                <flux:subheading>
                    <p>{{ $message }}</p>
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button wire:click="confirm" variant="danger">Confirm</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
