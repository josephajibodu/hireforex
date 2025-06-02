<x-layouts.guest>
    @section('title', 'Race to Top 4\' Weekly Reward')

    <section class="overflow-hidden bg-accent">
        <div class="mx-auto max-w-screen-lg">
            <div class="container py-36 !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                    <div class="md:w-7/12 lg:w-6/12 xl:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
                        <h1 class="text-[calc(1.365rem_+_1.38vw)] font-bold leading-[1.2] xl:text-[2.4rem] !mb-3 text-white">
                            Top 4 Traders Race-Top 4 Gets $300 This Week.
                        </h1>
                        <p class="lead text-white/80 lg:!px-[1.25rem] xl:!px-[1.25rem] xxl:!px-[2rem] leading-[1.65] text-[0.9rem] font-medium !mb-[.25rem]">
                            Exclusive weekly rewards for our top arbitrage traders of the week on Profitchain
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-16 md:pb-32">
        <div class="prose max-w-none">
            <div class="mb-12">
                <h2 class="text-3xl font-bold mb-4">Weekly Arbitrage Trading Challenge</h2>
                <p class="text-lg text-gray-600">
                    Profitchain rewards its members every week based on their total arbitrage trading volume. If you reach a specific trading threshold by the end of the week, you become eligible for exclusive rewards and benefits.
                </p>
                <br>
                 <p class="text-lg text-gray-600">
                    Climb the leaderboard to Top 4, Win the weekly prize.
                   The challenge begins every Sunday at 12 AM and ends every Saturday at 11 PM each week.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-3 text-accent">Claim 1st Place</h3>
                    <p class="text-gray-600">Win $140 (Directly From Victor)</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-3 text-accent">Claim 2nd Place</h3>
                    <p class="text-gray-600">Win $90 (Directly From Victor)</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-3 text-accent">Claim 3rd Place</h3>
                    <p class="text-gray-600">Win $40 (Directly From Victor)</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-3 text-accent">Claim 4th Place</h3>
                    <p class="text-gray-600">Win $30 (Directly From Victor)</p>
                </div>
            </div>

            @php
                $leaderboard = \App\Models\Leaderboard::query()
                    ->where('amount', '>', 0)
                    ->with(['user'])
                    ->orderByDesc('amount')
                    ->limit(50) // Limit to top 50 users
                    ->get();
            @endphp

            <div class="bg-white rounded-lg shadow-md p-6 mb-12">
                <h2 class="text-2xl font-semibold mb-6">Top 50 Profitchain Traders (This Week)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Traded (This week)</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($leaderboard as $index => $trader)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ obfuscate($trader->user->username, 4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ to_money($trader->amount, 100, '$') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-100 rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">How to Participate</h3>
                <p class="text-gray-600">
                    To participate in the Members' Reward Program, simply engage in arbitrage trading on the Profitchain platform. Your total trading volume for the week will be automatically calculated, and you'll be eligible for rewards based on the thresholds you reach. The weekly period runs from Sunday to Saturday, with rewards distributed at the end of the week(Saturday).
                </p>
            </div>
        </div>
    </section>
</x-layouts.guest>
