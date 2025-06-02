<x-layouts.guest>
    @section('title', 'Transparent and verifiable payout proofs')

    <section class="overflow-hidden bg-accent">
        <div class="mx-auto max-w-screen-lg">
            <div class="container py-36 !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                    <div class="md:w-7/12 lg:w-6/12 xl:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
                        <h1 class="text-[calc(1.365rem_+_1.38vw)] font-bold leading-[1.2] xl:text-[2.4rem] !mb-3 text-white">
                            Profitchain Payouts
                        </h1>
                        <p class="lead text-white/80 lg:!px-[1.25rem] xl:!px-[1.25rem] xxl:!px-[2rem] leading-[1.65] text-[0.9rem] font-medium !mb-[.25rem]">
                            Transparent and verifiable withdrawal proofs
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-16 md:pb-32">
        <div class="prose max-w-none">
            <div class="mb-12">
                <h2 class="text-3xl font-bold mb-4">Withdrawal Proofs</h2>
                <p class="text-lg text-gray-600">
                    To ensure transparency and verification, Profitchain publishes daily withdrawal proofs below. These records are time-stamped and updated every 48 hours. You can also download them for reference.
                </p>
            </div>

            <div class="bg-white rounded-lg">
                <flux:heading size="xl" class="text-2xl font-semibold mb-6">Latest Uploaded Profitchain Withdrawal Proofs</flux:heading>

                <div class="grid grid-cols-2 sm:flex flex-wrap gap-6">
                    @php
                        $payoutProofs = \App\Models\WithdrawalProof::query()->latest()->paginate(30);
                    @endphp

                    @foreach($payoutProofs as $payoutProof)
                        <div class="bg-white rounded-lg flex flex-col shadow-md">

                            <div class="relative aspect-[3/4] h-[250px] bg-gray-200 border border-accent rounded-lg overflow-hidden">
                                <img src="{{ Storage::url($payoutProof->content) }}" alt="Banner Image" class="w-full h-full object-cover object-left">
                                <flux:button
                                    icon="arrow-down-on-square"
                                    class="absolute! bottom-3 right-3 shadow-lg cursor-pointer"
                                    size="sm"
                                    variant="primary"
                                    href="{{ \Illuminate\Support\Facades\Storage::url($payoutProof->content) }}"
                                    download="true"
                                >
                                    Download
                                </flux:button>
                            </div>
                        </div>
                    @endforeach

                    <div class="py-2 w-full">
                        {{ $payoutProofs->links() }}
                    </div>
                </div>
            </div>

            <div class="mt-12 bg-gray-100 rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Important Note</h3>
                <p class="text-gray-600">
                    These withdrawal proofs are provided for transparency purposes. They are updated every 48 hours to reflect the most recent transactions. If you have any questions or concerns about a specific transaction, please contact our support team.
                </p>
            </div>
        </div>
    </section>
</x-layouts.guest>