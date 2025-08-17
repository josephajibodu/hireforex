<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    /**
     * Display active trades for the authenticated user
     */
    public function active()
    {
        $user = auth()->user();
        $trades = $user->activeTrades()
            ->with(['trader'])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('pages.trades.active', compact('trades'));
    }

    /**
     * Display trade history for the authenticated user
     */
    public function history()
    {
        $user = auth()->user();
        $trades = $user->trades()
            ->with(['trader'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.trades.history', compact('trades'));
    }

    /**
     * Display the specified trade
     */
    public function show(Trade $trade)
    {
        // Ensure user can only view their own trades
        $user = auth()->user();
        if ($trade->user_id !== $user->id) {
            abort(403);
        }

        return view('pages.trades.show', compact('trade'));
    }
}
