<x-layouts.app>
    @section('title', 'Trade History')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Trade History</flux:heading>

        <flux:subheading size="lg" class="mb-6">Your completed and refunded forex trades</flux:subheading>

        @if($trades->count() > 0)
            <div class="grid gap-4">
                @foreach($trades as $trade)
                    <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 {{ $trade->isCompleted() ? 'bg-green-100 dark:bg-green-900/20' : 'bg-blue-100 dark:bg-blue-900/20' }} rounded-full flex items-center justify-center">
                                    @if($trade->isCompleted())
                                        <flux:icon name="check-circle" class="w-6 h-6 text-green-600 dark:text-green-400" />
                                    @else
                                        <flux:icon name="refresh-cw" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                        Trade with {{ $trade->trader->name }}
                                    </h3>
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $trade->isCompleted() ? 'Completed' : 'Refunded' }} {{ $trade->completed_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold {{ $trade->isCompleted() ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    ${{ number_format($trade->amount, 2) }}
                                </div>
                                <div class="text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $trade->isCompleted() ? 'Investment' : 'Refunded' }}
                                </div>
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
                            <div class="text-center p-3 {{ $trade->isCompleted() ? 'bg-green-50 dark:bg-green-900/20' : 'bg-blue-50 dark:bg-blue-900/20' }} rounded-lg">
                                <div class="text-lg font-semibold {{ $trade->isCompleted() ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    @if($trade->isCompleted())
                                        ${{ number_format($trade->potential_return_amount, 2) }}
                                    @else
                                        ${{ number_format($trade->mbg_refund_amount, 2) }}
                                    @endif
                                </div>
                                <div class="text-xs {{ $trade->isCompleted() ? 'text-green-700 dark:text-green-300' : 'text-blue-700 dark:text-blue-300' }}">
                                    {{ $trade->isCompleted() ? 'Returns' : 'MBG Refund' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                                <strong>Trade Period:</strong> {{ $trade->start_date->format('M j, Y') }} - {{ $trade->end_date->format('M j, Y') }}
                            </div>
                            <flux:button
                                href="{{ route('trades.show', $trade) }}"
                                wire:navigate="true"
                                variant="outline"
                                size="sm"
                            >
                                View Details
                            </flux:button>
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
                <flux:icon name="file-clock" class="w-16 h-16 text-neutral-400 mx-auto mb-4" />
                <flux:heading size="lg" class="text-neutral-600 dark:text-neutral-400 mb-2">No Trade History</flux:heading>
                <p class="text-neutral-500 dark:text-neutral-500 mb-6">
                    You haven't completed any trades yet. Start by hiring a trader!
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
