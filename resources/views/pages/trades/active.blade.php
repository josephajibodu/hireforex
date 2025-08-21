<x-layouts.app>
    @section('title', 'Active Trades')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Active Trades</flux:heading>

        <flux:subheading size="lg" class="mb-6">Your currently active traders</flux:subheading>

        @if($trades->count() > 0)
            <div class="grid gap-4">
                @foreach($trades as $trade)
                    <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-brand-100 dark:bg-brand-900/20 rounded-full flex items-center justify-center">
                                    <flux:icon name="chart-candlestick" class="w-6 h-6 text-brand-600 dark:text-brand-400" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                        Trade with {{ $trade->trader->name }}
                                    </h3>
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                        Hired {{ $trade->start_date?->diffForHumans() ?? 'recently' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($trade->amount, 2) }}
                                </div>
                                <div class="text-sm text-neutral-600 dark:text-neutral-400">Capital</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                                    {{ $trade->potential_return }}%
                                </div>
                                <div class="text-xs text-green-700 dark:text-green-300">Potential Return</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                    {{ $trade->mbg_rate }}%
                                </div>
                                <div class="text-xs text-blue-700 dark:text-blue-300">MBG Rate</div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                                    {{ $trade->duration_days }}d
                                </div>
                                <div class="text-xs text-purple-700 dark:text-purple-300">Duration</div>
                            </div>
                            <div class="text-center p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                                <div class="text-lg font-semibold text-orange-600 dark:text-orange-400">
                                    <div x-data="{
                                        timeLeft: {{ $trade->getTimeRemainingAttribute() ?? 0 }},
                                        updateTimer() {
                                            if (this.timeLeft > 0) {
                                                this.timeLeft--;
                                                setTimeout(() => this.updateTimer(), 1000);
                                            }
                                        },
                                        formatTime() {
                                            if (this.timeLeft <= 0) return 'Expired';

                                            const days = Math.floor(this.timeLeft / 86400);
                                            const hours = Math.floor((this.timeLeft % 86400) / 3600);
                                            const minutes = Math.floor((this.timeLeft % 3600) / 60);
                                            const seconds = this.timeLeft % 60;

                                            if (days > 0) {
                                                return `${days}d ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                                            } else {
                                                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                                            }
                                        }
                                    }" x-init="updateTimer()">
                                        <span x-show="timeLeft > 0" x-text="formatTime()"></span>
                                        <span x-show="timeLeft <= 0" class="text-red-500">Expired</span>
                                    </div>
                                </div>
                                <div class="text-xs text-orange-700 dark:text-orange-300">Time Left</div>
                            </div>
                        </div>

                        <div class="text-sm text-neutral-600 dark:text-neutral-400">
                            <strong>Expected Completion:</strong> {{ $trade->end_date?->format('M j, Y g:i A') ?? 'N/A' }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($trades->hasPages())
                <div class="mt-8">
                    {{ $trades->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <flux:icon name="chart-candlestick" class="w-16 h-16 text-neutral-400 mx-auto mb-4" />
                <flux:heading size="lg" class="text-neutral-600 dark:text-neutral-400 mb-2">No Active Trades</flux:heading>
                <p class="text-neutral-500 dark:text-neutral-500 mb-6">
                    You don't have any active trades at the moment. Start by hiring a trader!
                </p>
                <flux:button
                    href="{{ route('traders.index') }}"
                    wire:navigate="true"
                    variant="primary"
                >
                    Hire a Trader
                </flux:button>
            </div>
        @endif
    </div>
</x-layouts.app>
