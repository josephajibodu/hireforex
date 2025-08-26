<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component {
    public int $limit = 0;

    #[On('top-up-submitted')]
    public function mount(int $limit = 0)
    {
        $this->limit = $limit;
    }

    public function getTopUps()
    {
        $query = Auth::user()->topUps()->latest();

        if ($this->limit > 0) {
            $query->limit($this->limit);
        }

        return $query->paginate();
    }
}; ?>

<div class="w-full overflow-x-auto">
    <x-table class="bg-gray-50 rounded-none! border-0 hidden md:table">
        <x-table.columns>
            <x-table.column>Reference</x-table.column>
            <x-table.column class="text-center">Amount (USDT)</x-table.column>
            <x-table.column>Payment Method</x-table.column>
            <x-table.column>Status</x-table.column>
            <x-table.column class="text-center">Date</x-table.column>
        </x-table.columns>

        <x-table.rows x-data>
            @forelse($this->getTopUps() as $topUp)
                <x-table.row class="cursor-pointer">
                    <x-table.cell class="min-w-24 capitalize">{{ $topUp->reference }}</x-table.cell>
                    <x-table.cell class="text-center">
                        <flux:heading>{{ to_money($topUp->amount, 1, '$') }}</flux:heading>
                    </x-table.cell>
                    <x-table.cell>
                        <flux:badge color="{{ $topUp->method->getFluxColor() }}">
                            {{ $topUp->method->getLabel() }}
                        </flux:badge>
                    </x-table.cell>
                    <x-table.cell>
                        <x-flux::badge color="{{ $topUp->status->getFluxColor() }}">
                            {{ ucfirst($topUp->status->getLabel()) }}
                        </x-flux::badge>
                    </x-table.cell>
                    <x-table.cell class="text-center">{{ $topUp->created_at->format('d M h:i A') }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="4" class="text-center">No top-up history found</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    <div class="md:hidden">
        @forelse($this->getTopUps() as $topUp)
            <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Reference:</flux:heading>
                    <flux:subheading class="uppercase text-end">{{ $topUp->reference }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Amount:</flux:heading>
                    <flux:subheading>{{ to_money($topUp->amount, 1, '$') }}</flux:subheading>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Payment Method:</flux:heading>
                    <flux:badge color="{{ $topUp->method->getFluxColor() }}">
                        {{ $topUp->method->getLabel() }}
                    </flux:badge>
                </div>
                <div class="flex justify-between py-1">
                    <flux:heading class="font-semibold">Status:</flux:heading>
                    <x-flux::badge color="{{ $topUp->status === 'pending' ? 'amber' : ($topUp->status === 'completed' ? 'green' : 'red') }}">
                        {{ ucfirst($topUp->status->getLabel()) }}
                    </x-flux::badge>
                </div>
                <div class="flex justify-between">
                    <flux:heading class="font-semibold">Date:</flux:heading>
                    <flux:subheading>{{ $topUp->created_at->format('d M h:i A') }}</flux:subheading>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border rounded-lg p-4 text-center">
                <flux:subheading>No top-up history found</flux:subheading>
            </div>
        @endforelse
    </div>

    @if(!$limit)
        <div class="my-2">
            {{ $this->getTopUps()->links() }}
        </div>
    @endif
</div>
