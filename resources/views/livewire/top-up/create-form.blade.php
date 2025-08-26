<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\TopUp;
use Illuminate\Support\Facades\Auth;
use App\Settings\GeneralSetting;

new class extends Component {
    use WithFileUploads;

    public $bybit_uid;
    public $binance_uid;
    public $usdt_trc;
    public $usdt_bep;

    #[Validate('required|numeric|min:1')]
    public $amount = '';

    #[Validate('required|string|in:bybit,usdt,binance')]
    public string $payment_method = 'bybit';

    public string $bybit_email = '';

    #[Validate('required|image|max:2048')]
    public $screenshot = null;

    public function mount(GeneralSetting $settings, $type = null)
    {
        if (in_array($type, ['bybit', 'usdt', 'binance'])) {
            $this->payment_method = $type;
        }
        $this->bybit_uid = $settings->bybit_uid;
        $this->binance_uid = $settings->binance_uid;
        $this->usdt_trc = $settings->usdt_trc;
        $this->usdt_bep = $settings->usdt_bep;
    }

    public function create()
    {
        $rules = [
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:bybit,usdt,binance',
            'screenshot' => 'required|image|max:2048',
        ];
        if (in_array($this->payment_method, ['bybit', 'binance'])) {
            $rules['bybit_email'] = 'required|email|max:255';
        }
        $this->validate($rules);

        try {
            $path = $this->screenshot->store('top-ups');

            $topUp = TopUp::create([
                'reference' => 'TOP-' . Str::random(10),
                'user_id' => Auth::id(),
                'amount' => $this->amount,
                'method' => $this->payment_method,
                'bybit_email' => in_array($this->payment_method, ['bybit', 'binance']) ? $this->bybit_email : null,
                'screenshot' => $path,
                'status' => \App\Enums\TopupStatus::Pending,
            ]);

            $this->dispatch('flash-success',
                title: "Your top-up details have been successfully submitted.",
                message: "If the information and payment are correct, your HireForex account will be credited within 10 minutes."
            );

            $this->reset(['amount', 'payment_method', 'bybit_email', 'screenshot']);
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('flash-error', message: $ex->getMessage() ?? 'Top-up request failed');
        }
    }
}; ?>

<div>
    <div class="mb-8">
                    <flux:heading size="xl" level="1">Top Up Your HireForex Balance</flux:heading>
        <div class="rounded-xl py-6 mb-6 mt-4">
            @if($payment_method === 'bybit')
                <ol class="list-decimal text-sm list-inside text-base text-zinc-800 space-y-1 mb-2 px-4">
                    <li>You can fund your HireForex wallet using <b>Bybit UID transfer</b>.</li>
                    <li>After making the transfer, submit your Bybit email, amount, and screenshot for confirmation.</li>
                    <li>Your HireForex wallet will be credited within 10 minutes after confirmation.</li>
                    <li>If payment is not received or invalid, your top-up request will be rejected and you will be notified.</li>
                </ol>
                <div class="mt-4">
                    <div class="font-semibold mb-1">HireForex UID:</div>
                    <flux:input.group class="inline-flex items-center">
                        <flux:input icon="badge-dollar-sign" value="{{ $bybit_uid }}" readonly copyable />
                    </flux:input.group>
                </div>
            @elseif($payment_method === 'usdt')
                <ol class="list-decimal list-inside text-sm text-zinc-800 space-y-1 mb-2 px-4">
                    <li>You can fund your HireForex wallet using <b>USDT transfer</b> (TRC-20 or BEP-20).</li>
                    <li>After making the transfer, submit your amount and screenshot for confirmation.</li>
                    <li>Your HireForex wallet will be credited within 10 minutes after confirmation.</li>
                    <li>If payment is not received or invalid, your top-up request will be rejected and you will be notified.</li>
                </ol>
                <div class="mt-4 flex flex-col gap-4">
                    <div class="flex-1">
                        <div class="font-semibold mb-1">Network: TRC-20</div>
                        <flux:input.group class="inline-flex items-center">
                            <flux:input value="{{ $usdt_trc }}" readonly copyable />
                        </flux:input.group>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold mb-1">Network: BEP-20</div>
                        <flux:input.group class="inline-flex items-center">
                            <flux:input value="{{ $usdt_bep }}" readonly copyable />
                        </flux:input.group>
                    </div>
                </div>
            @else
                <ol class="list-decimal list-inside text-sm text-zinc-800 space-y-1 mb-2 px-4">
                    <li>You can fund your HireForex wallet using <b>Binance transfer</b>.</li>
                    <li>After making the transfer, submit your amount and screenshot for confirmation.</li>
                    <li>Your HireForex wallet will be credited within 10 minutes after confirmation.</li>
                    <li>If payment is not received or invalid, your top-up request will be rejected and you will be notified.</li>
                </ol>
                <div class="mt-4">
                    <div class="font-semibold mb-1">HireForex Binance UID:</div>
                    <flux:input.group class="inline-flex items-center">
                        <flux:input icon="badge-dollar-sign" value="{{ $binance_uid }}" readonly copyable />
                    </flux:input.group>
                </div>
            @endif
        </div>
    </div>

    <form wire:submit="create" class="space-y-6">
        <flux:input.group>
            <flux:input
                type="number"
                step="0.01"
                min="1"
                name="amount"
                wire:model="amount"
                placeholder="Enter amount"
                required
            />
            <flux:input.group.suffix>USD</flux:input.group.suffix>
        </flux:input.group>
        <flux:error name="amount" />

        <flux:select
            name="payment_method"
            wire:model.live="payment_method"
            label="Payment Method"
            required
        >
            <flux:select.option value="bybit">Bybit Transfer</flux:select.option>
            <flux:select.option value="usdt">USDT Transfer</flux:select.option>
            <flux:select.option value="binance">Binance Transfer</flux:select.option>
        </flux:select>
        <flux:error name="payment_method" />

        @if(in_array($payment_method, ['bybit', 'binance']))
            <flux:input
                type="email"
                name="bybit_email"
                wire:model="bybit_email"
                label="{{ $payment_method === 'bybit' ? 'Bybit Email' : 'Binance Email' }}"
                placeholder="Enter your {{ $payment_method === 'bybit' ? 'Bybit' : 'Binance' }} email"
                required
            />
            <flux:error name="bybit_email" />
        @endif

        <flux:input
            type="file"
            accept="image/*"
            name="screenshot"
            wire:model="screenshot"
            label="Upload Payment Screenshot"
            required
        />
        <flux:error name="screenshot" />

        @if($screenshot)
            <div class="mt-2">
                <img src="{{ $screenshot->temporaryUrl() }}" class="max-w-xs rounded-lg" alt="Payment Screenshot Preview">
            </div>
        @endif

        <div class="flex">
            <flux:spacer/>
            <flux:button variant="primary" wire:loading.attr="disabled" type="submit">
                Confirm Top-up
            </flux:button>
        </div>
    </form>
</div>
