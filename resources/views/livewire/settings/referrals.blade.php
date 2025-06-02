
<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Enums\ReferralStatus;
use Livewire\WithPagination;
use App\Models\SocialMediaPost;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithPagination;

    public float $totalEarnings = 0;
    public \Illuminate\Database\Eloquent\Collection $socialMediaPosts;

    public function mount(): void
    {
        $user = Auth::user();

        $this->socialMediaPosts = SocialMediaPost::query()->get();
        $this->totalEarnings = $user->referrals()->where('status', ReferralStatus::Completed)->sum('bonus');
    }

    public function download(string $path)
    {
        return Storage::download($path);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    @php
        $value = is_referral_flat() ? to_money(get_referral_bonus(), 1, '$') : get_referral_bonus() * 100;
        $subheading = is_referral_flat()
            ? "Refer someone to Profitchain and get a flat {$value} bonus when they make their first Arbitrage market trade, regardless of the trade amount! 🚀"
            : "Refer someone to Profitchain and get {$value}% of their first Arbitrage market trade. For example, if you refer someone and their first trade is $100 USD, you get ".to_money(get_referral_bonus() * 100, 1, '$').".";
    @endphp

    <x-settings.layout
        heading="{{ __('Refer a friend') }}"
        subheading="{{ $subheading }}"
    >
        <div class="my-6 w-full space-y-6 flex flex-col md:flex-row md:justify-between md:items-center">
            <flux:heading class="md:mb-0 mb-2" size="lg">Invite link</flux:heading>
            <div class="flex justify-between gap-4 flex-1 max-w-sm">
                <flux:input
                    value="{{ route('register', ['code' => Auth::user()->referral_code]) }}"
                    readonly
                    copyable
                    type="text"
                    class="mb-0"
                />
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mt-12">

            <!-- Stat Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg">
                <flux:heading>Total Referrals</flux:heading>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ Auth::user()->referrals()->count() }}</h2>
            </div>

            <!-- Stat Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg">
                <flux:heading>Earnings</flux:heading>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ to_money($totalEarnings,  1, '$') }}</h2>
            </div>

            <!-- Stat Card -->
            <div class="border overflow-hidden bg-white dark:bg-neutral-800 p-4 rounded-lg">
                <flux:heading>Referral Clicks</flux:heading>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mt-2">{{ Auth::user()->referral_link_view_count ?? 0 }}</h2>
            </div>

        </div>

        <!-- Referral table -->
        <div class="mt-12">
            <flux:heading class="mb-4" size="lg">Referrals</flux:heading>
            <x-table class="bg-gray-50 shadow-lg">
                <x-table.columns>
                    <x-table.column >User</x-table.column>
                    <x-table.column>Amount</x-table.column>
                    <x-table.column class="text-center hidden md:table-cell">Date</x-table.column>
                    <x-table.column class="text-center">Status</x-table.column>
                </x-table.columns>

                @php
                    $referrals = Auth::user()->referrals()->with('referredUser')->paginate();
                @endphp

                <x-table.rows>
                    @forelse($referrals as $referral)
                        <x-table.row>
                            <x-table.cell class="min-w-24 capitalize">
                                {{ $referral->referredUser->username }}
                            </x-table.cell>
                            <x-table.cell class="">
                                {{ to_money($referral->bonus, 1, '$') }}
                                <flux:subheading class="mt-1 text-xs">{{ \Carbon\Carbon::parse($referral->created_at)->format('d M Y') }}</flux:subheading>
                            </x-table.cell>
                            <x-table.cell class="text-center hidden md:table-cell" >{{ \Carbon\Carbon::parse($referral->created_at)->format('d M Y') }}</x-table.cell>
                            <x-table.cell class="text-center">
                                @switch($referral->status)
                                    @case(\App\Enums\ReferralStatus::Pending)
                                        <x-flux::badge color="amber">Awaiting Trade</x-flux::badge>
                                        @break
                                    @case(\App\Enums\ReferralStatus::Completed)
                                        <x-flux::badge color="green">Traded</x-flux::badge>
                                        @break
                                    @case(\App\Enums\ReferralStatus::Invalidated)
                                        <x-flux::badge color="red">Invalidated</x-flux::badge>
                                        @break
                                @endswitch
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="4" class="text-center">No referrals yet!</x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-table.rows>
            </x-table>
        </div>

        <div class="my-2">
            {{ $referrals->links() }}
        </div>

        <!-- Referral Instructions -->
        <div class="mt-12">
            <flux:heading class="mb-4" size="lg">Referral Instructions</flux:heading>
            <p class="text-sm">You can earn extra income by referring people to Profitchain. Share your referral link via WhatsApp, Facebook, Instagram, or TikTok to maximize your reach.</p>
            <ul class="list-disc pl-5 mt-3 text-gray-500 text-sm">
                <li>Earn {{ is_referral_flat() ? "$value flat on" : "{$value}% of" }} your referral’s first trade.</li>
                <li>Advertise your referral link on social media.</li>
                <li>Download and share pre-made social media posts & banners.</li>
            </ul>
        </div>

        <!-- Sharable Banners -->
        <div class="mt-6">
            <flux:heading class="mb-4" size="lg">Pre-made Social Media Banners</flux:heading>

            <div class="flex overflow-x-auto gap-6 ">
                @foreach($socialMediaPosts as $post)
                    <div class="border w-[300px] bg-white dark:bg-neutral-800 rounded-lg p-4 flex flex-col shadow-md">
                        <flux:heading class="mb-2 text-center text-lg font-semibold">
                            {{ $post->platform->getLabel() }} Banner {{ $loop->index + 1 }}
                        </flux:heading>

                        <div class="relative aspect-[3/4] h-[250px] bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($post->image) }}" alt="Banner Image" class="w-full h-full object-cover">
                            <flux:button
                                icon="arrow-down-on-square"
                                class="absolute! bottom-3 right-3 shadow-lg cursor-pointer"
                                size="sm"
                                variant="primary"
                                wire:click="download('{{ $post->image }}')"
                            >
                                Download
                            </flux:button>
                        </div>

                        <div class="mt-3 flex flex-col items-start justify-between gap-2" x-data="{ copied: false }">
                            <p class="text-sm text-gray-700 dark:text-gray-300 flex-1 line-clamp-3">{{ $post->content }}</p>
                            <div class="flex justify-between items-center">
                                <flux:button
                                        icon="clipboard-document"
                                        class="cursor-pointer"
                                        size="xs"
                                        variant="primary"
                                        @click="navigator.clipboard.writeText(`{{ $post->content }}`).then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                >Copy Post Content</flux:button>

                                <flux:heading x-show="copied" class="text-green-500! text-xs ml-2">Copied!</flux:heading>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Referral FAQ -->
        <div class="mt-12">
            <flux:heading class="mb-4" size="lg">Profitchain Referral Program FAQ</flux:heading>
            <div class="space-y-4">
                <div>
                    <flux:heading>How does the referral program work?</flux:heading>
                    <flux:subheading>You earn {{ is_referral_flat() ? $value : "{$value}%" }} of a referred user’s first trade. Share your referral link via social media or WhatsApp to maximize your earnings. Referral statistics and earnings can be viewed on your Referral Dashboard.</flux:subheading>
                </div>
                <div>
                    <flux:heading>How do I know if my referral has traded?</flux:heading>
                    <flux:subheading>You can track your referrals through the Referral Dashboard, where you will see their username and trade status.</flux:subheading>
                </div>
                <div>
                    <flux:heading>What happens if I refer someone who never trades?</flux:heading>
                    <flux:subheading>If your referral does not trade, you will not receive any commission.</flux:subheading>
                </div>
                <div>
                    <flux:heading>Is there a limit on how many people I can refer?</flux:heading>
                    <flux:subheading>No, there is no limit on referrals. You can refer as many people as possible to maximize your earnings.</flux:subheading>
                </div>
            </div>
        </div>

    </x-settings.layout>

</section>
